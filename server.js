// server.js (ESM, Node >= 18)
import express from "express";
import { Server } from "socket.io";
import http from "http";
import "dotenv/config";

const PORT = process.env.SOCKET_PORT || 3001;
const PREFIX = process.env.SOCKET_PREFIX || "Jbrad2023";
const LARAVEL_API = process.env.LARAVEL_API; // e.g. https://azharmaterial.com
const TRACK_TOKEN = process.env.TRACK_API_TOKEN; // must match Laravel
const CORS_ORIGIN = process.env.CORS_ORIGIN || "*";

if (!LARAVEL_API) throw new Error("LARAVEL_API is required");
if (!TRACK_TOKEN) throw new Error("TRACK_API_TOKEN is required");

const app = express();
const server = http.createServer(app);
const io = new Server(server, { cors: { origin: CORS_ORIGIN } });

// presence counter via heartbeat
const publicLastSeen = new Map(); // socket.id -> ts
const onlinePublicCount = () => {
  const now = Date.now();
  for (const [id, ts] of publicLastSeen.entries()) {
    if (now - ts > 60000) publicLastSeen.delete(id);
  }
  return publicLastSeen.size;
};

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
    return await resp.json();
  } catch (e) {
    console.warn("Snapshot fetch error:", e?.message || e);
    return null;
  }
}

const withPresence = (snap) => ({
  ...(snap || {}),
  onlineVisitors: onlinePublicCount(),
});

// health & debug
app.get("/health", (_req, res) => res.send("ok"));
app.get("/snapshot", async (_req, res) => {
  const snap = await fetchSnapshot();
  res.json(withPresence(snap || {}));
});

io.on("connection", (socket) => {
  socket.on("join", ({ room }) => room && socket.join(room));

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
    (async () => {
      const snap = await fetchSnapshot();
      io.to(`${PREFIX}-analytics`).emit(
        "analytics-update",
        withPresence(snap || {})
      );
    })();
  });

  socket.on("track-visitor", async (payload = {}) => {
    await persistVisit(payload); // disimpan ke Laravel (unique di DB)
    const snap = await fetchSnapshot(); // angka dihitung dari DB -> stabil
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

server.listen(PORT, () => console.log(`🚀 Socket server on :${PORT}`));
