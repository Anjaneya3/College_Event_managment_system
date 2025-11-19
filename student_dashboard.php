<?php
// student_dashboard.php
session_start();
require 'config.php';
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit;
}
$student_id = $_SESSION['student_id'];
// fetch student's name
$student_name = $_SESSION['student_name'] ?? 'Student';

// fetch upcoming events
$stmt = $conn->prepare("SELECT id, title, event_date, venue FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");
$stmt->execute();
$events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// fetch notifications
$stmt = $conn->prepare("SELECT n.id, n.title, n.message, n.created_at, a.name as admin_name FROM notifications n JOIN admins a ON n.admin_id = a.id ORDER BY n.created_at DESC LIMIT 10");
$stmt->execute();
$notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// fetch registered events for this student
$stmt = $conn->prepare("SELECT e.* FROM events e JOIN registrations r ON e.id = r.event_id WHERE r.student_id = ? ORDER BY e.event_date ASC");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$registered = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Student Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body>
<header><h2 class="logo">College Event Management</h2><nav><a href="index.php">Home</a> | <a href="logout.php">Logout</a></nav></header>

<section class="container">
  <h2>Welcome, <?php echo htmlspecialchars($student_name); ?></h2>

  <h3>Your Registered Events</h3>
  <?php if(count($registered) === 0): ?>
    <p>You haven't registered for any events yet.</p>
  <?php else: ?>
    <ul>
    <?php foreach($registered as $r): ?>
      <li><?php echo htmlspecialchars($r['title']) . ' — ' . date('d M Y', strtotime($r['event_date'])); ?></li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <h3>Upcoming Events</h3>
  <div class="event-grid">
    <?php foreach($events as $e): ?>
      <div class="event-card">
        <h3><?php echo htmlspecialchars($e['title']); ?></h3>
        <p><?php echo date('d M Y', strtotime($e['event_date'])); ?> — <?php echo htmlspecialchars($e['venue']); ?></p>
        <form method="post" action="register_event.php">
          <input type="hidden" name="event_id" value="<?php echo $e['id']; ?>">
          <button type="submit">Register for Event</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

  <h3>Notifications</h3>
  <?php if(count($notifications) === 0): ?>
    <p>No notifications yet.</p>
  <?php else: ?>
    <?php foreach($notifications as $n): ?>
      <div class="event-card">
        <strong><?php echo htmlspecialchars($n['title']); ?></strong>
        <p><?php echo nl2br(htmlspecialchars($n['message'])); ?></p>
        <small>By <?php echo htmlspecialchars($n['admin_name']); ?> on <?php echo $n['created_at']; ?></small>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</section>

<footer><p>© <?php echo date('Y'); ?></p></footer>
</body>
</html>
