const express = require("express");
const http = require("http");
const socketIo = require("socket.io");
const cors = require("cors");

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
  cors: {
    origin: ["http://localhost:3000", "http://localhost:8000"],
    methods: ["GET", "POST"],
  },
});

// Enable CORS
app.use(cors());
app.use(express.json());

// Store connected clients
const connectedClients = new Set();

// Analytics data
let analyticsData = {
  totalVisitors: 0,
  todayVisitors: 0,
  thisWeekVisitors: 0,
  thisMonthVisitors: 0,
  topPages: [],
  visitorsByDay: [],
};

io.on("connection", (socket) => {
  console.log("Client connected:", socket.id);
  connectedClients.add(socket.id);

  // Send current analytics data to new client
  socket.emit("analytics-update", analyticsData);

  // Handle visitor tracking
  socket.on("track-visitor", (visitorData) => {
    console.log("New visitor tracked:", visitorData);

    // Update analytics data
    analyticsData.totalVisitors++;

    // Update top pages
    const pageIndex = analyticsData.topPages.findIndex(
      (p) => p.page_visited === visitorData.page_visited
    );
    if (pageIndex >= 0) {
      analyticsData.topPages[pageIndex].count++;
    } else {
      analyticsData.topPages.push({
        page_visited: visitorData.page_visited,
        count: 1,
      });
    }

    // Sort top pages by count
    analyticsData.topPages.sort((a, b) => b.count - a.count);
    analyticsData.topPages = analyticsData.topPages.slice(0, 5);

    // Broadcast to all connected clients
    io.emit("analytics-update", analyticsData);
  });

  // Handle analytics refresh request
  socket.on("refresh-analytics", () => {
    console.log("Analytics refresh requested");
    io.emit("analytics-update", analyticsData);
  });

  // Handle disconnect
  socket.on("disconnect", () => {
    console.log("Client disconnected:", socket.id);
    connectedClients.delete(socket.id);
  });
});

// API endpoint to update analytics from Laravel
app.post("/api/analytics/update", (req, res) => {
  const {
    totalVisitors,
    todayVisitors,
    thisWeekVisitors,
    thisMonthVisitors,
    topPages,
    visitorsByDay,
  } = req.body;

  analyticsData = {
    totalVisitors: totalVisitors || analyticsData.totalVisitors,
    todayVisitors: todayVisitors || analyticsData.todayVisitors,
    thisWeekVisitors: thisWeekVisitors || analyticsData.thisWeekVisitors,
    thisMonthVisitors: thisMonthVisitors || analyticsData.thisMonthVisitors,
    topPages: topPages || analyticsData.topPages,
    visitorsByDay: visitorsByDay || analyticsData.visitorsByDay,
  };

  // Broadcast to all connected clients
  io.emit("analytics-update", analyticsData);

  res.json({ success: true, message: "Analytics updated" });
});

// API endpoint to get current analytics
app.get("/api/analytics", (req, res) => {
  res.json(analyticsData);
});

const PORT = process.env.PORT || 3001;

server.listen(PORT, () => {
  console.log(`Socket.IO server running on port ${PORT}`);
  console.log(
    `Analytics API available at http://localhost:${PORT}/api/analytics`
  );
});
