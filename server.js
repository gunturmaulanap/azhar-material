import express from "express";
import { Server } from "socket.io";
import http from "http";
import "dotenv/config";

const PORT = process.env.SOCKET_PORT || 3001;
const PREFIX = process.env.SOCKET_PREFIX || "Jbrad2023";
const LARAVEL_API = process.env.LARAVEL_API || "https://azharmaterial.com"; // your Laravel app URL
// must match Laravel .env
const TRACK_TOKEN = process.env.TRACK_API_TOKEN;
if (!TRACK_TOKEN) {
  throw new Error("TRACK_API_TOKEN is required (set it in .env or environment variables)");
}

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
  cors: { origin: process.env.CORS_ORIGIN || "*" },
});

// Count ONLY public visitors (those who emit `public-online`) with a heartbeat
const publicLastSeen = new Map(); // socket.id -> timestamp (ms)
function onlinePublicCount() {
  const now = Date.now();
  // drop any socket that hasn't heartbeated in the last 60s
  for (const [id, ts] of publicLastSeen.entries()) {
    if (now - ts > 60000) publicLastSeen.delete(id);
  }
  return publicLastSeen.size;
}

// --- helpers ---
async function persistVisit(payload = {}) {
  try {
    await fetch(`${LARAVEL_API}/api/visits`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Track-Token": TRACK_TOKEN,
      },
      body: JSON.stringify({
        visitor_id: payload.visitor_id,
        ip_address: payload.ip_address,
        user_agent: payload.user_agent,
        page_visited: payload.page_visited || "/",
        referrer: payload.referrer || null,
        timestamp: payload.timestamp || new Date().toISOString(),
      }),
    });
  } catch (e) {
    console.warn("Persist error:", e?.message || e);
  }
}

async function fetchSnapshot() {
  try {
    const resp = await fetch(`${LARAVEL_API}/api/analytics/snapshot`, {
      headers: { "X-Track-Token": TRACK_TOKEN },
    });
    const data = await resp.json();
    return data;
  } catch (e) {
    console.warn("Snapshot fetch error:", e?.message || e);
    return null;
  }
}

function withPresence(snap) {
  return { ...(snap || {}), onlineVisitors: onlinePublicCount() };
}

// Simple health & snapshot endpoints
app.get("/health", (_req, res) => res.send("ok"));
app.get("/snapshot", async (_req, res) => {
  const snap = await fetchSnapshot();
  res.json(withPresence(snap || {}));
});

io.on("connection", (socket) => {
  console.log("✅ Client connected", socket.id);

  socket.on("join", ({ room }) => room && socket.join(room));

  // First snapshot on connect
  (async () => {
    const snap = await fetchSnapshot();
    socket.emit("analytics-update", withPresence(snap || {}));
  })();

  socket.on("request-snapshot", async () => {
    const snap = await fetchSnapshot();
    socket.emit("analytics-update", withPresence(snap || {}));
  });

  socket.on("public-online", () => {
    publicLastSeen.set(socket.id, Date.now());
    socket.join(`${PREFIX}-public`);
    // notify dashboards that presence changed
    (async () => {
      const snap = await fetchSnapshot();
      io.to(`${PREFIX}-analytics`).emit(
        "analytics-update",
        withPresence(snap || {})
      );
    })();
  });

  socket.on("track-visitor", async (payload = {}) => {
    // Always persist visit; DISTINCT(visitor_id) di Laravel yang bikin unik
    await persistVisit(payload);
    const snap = await fetchSnapshot();
    io.to(`${PREFIX}-analytics`).emit(
      "analytics-update",
      withPresence(snap || {})
    );
  });

  socket.on("disconnect", () => {
    publicLastSeen.delete(socket.id);
    (async () => {
      const snap = await fetchSnapshot();
      io.to(`${PREFIX}-analytics`).emit(
        "analytics-update",
        withPresence(snap || {})
      );
    })();
  });
});

server.listen(PORT, () => {
  console.log(`🚀 Server listening on :${PORT}`);
});
