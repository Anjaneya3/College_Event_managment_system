<?php
// admin_dashboard.php
session_start();
require 'config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit; }
$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// handle add event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_event') {
        $title = $_POST['title']; $date = $_POST['event_date']; $time = $_POST['event_time']; $venue = $_POST['venue']; $desc = $_POST['description'];
        $stmt = $conn->prepare("INSERT INTO events (title, event_date, event_time, venue, description, created_by_admin) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $title, $date, $time, $venue, $desc, $admin_id);
        $stmt->execute();
    } elseif ($_POST['action'] === 'delete_event' && isset($_POST['event_id'])) {
        $eid = (int)$_POST['event_id'];
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i",$eid);
        $stmt->execute();
    } elseif ($_POST['action'] === 'notify') {
        $title = $_POST['title']; $msg = $_POST['message'];
        $stmt = $conn->prepare("INSERT INTO notifications (admin_id, title, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $admin_id, $title, $msg);
        $stmt->execute();
    }
}

// fetch events
$events = $conn->query("SELECT e.*, a.name as admin_name FROM events e LEFT JOIN admins a ON e.created_by_admin = a.id ORDER BY event_date DESC")->fetch_all(MYSQLI_ASSOC);

// fetch registrations
$regs = $conn->query("SELECT r.id, s.name AS student_name, s.roll_no, e.title AS event_title, r.registered_at FROM registrations r JOIN students s ON r.student_id=s.id JOIN events e ON r.event_id=e.id ORDER BY r.registered_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body>
<header><h2 class="logo">Admin Dashboard</h2><nav><a href="index.php">Home</a> | <a href="logout.php">Logout</a></nav></header>

<section class="container">
  <h2>Welcome, <?php echo htmlspecialchars($admin_name); ?></h2>

  <h3>Add Event</h3>
  <div class="form-wrap">
    <form method="post">
      <input type="hidden" name="action" value="add_event">
      <input name="title" placeholder="Event title" required>
      <input name="event_date" type="date" required>
      <input name="event_time" type="time">
      <input name="venue" placeholder="Venue">
      <textarea name="description" placeholder="Short description"></textarea>
      <button type="submit">Add Event</button>
    </form>
  </div>

  <h3>All Events</h3>
  <?php foreach($events as $e): ?>
    <div class="event-card">
      <strong><?php echo htmlspecialchars($e['title']); ?></strong>
      <p><?php echo date('d M Y', strtotime($e['event_date'])); ?> <?php echo $e['venue'] ? ' | '.$e['venue'] : ''; ?></p>
      <form method="post" style="margin-top:8px;">
        <input type="hidden" name="action" value="delete_event">
        <input type="hidden" name="event_id" value="<?php echo $e['id']; ?>">
        <button type="submit">Delete Event</button>
      </form>
    </div>
  <?php endforeach; ?>

  <h3>Send Notification</h3>
  <div class="form-wrap">
    <form method="post">
      <input type="hidden" name="action" value="notify">
      <input name="title" placeholder="Notification title" required>
      <textarea name="message" placeholder="Message" required></textarea>
      <button type="submit">Send</button>
    </form>
  </div>

  <h3>Registered Students</h3>
  <?php if(count($regs) === 0): echo "<p>No registrations yet.</p>"; else: ?>
    <table style="width:100%; border-collapse: collapse;">
      <thead><tr><th style="text-align:left">Student</th><th>Roll</th><th>Event</th><th>When</th></tr></thead>
      <tbody>
        <?php foreach($regs as $r): ?>
          <tr><td><?php echo htmlspecialchars($r['student_name']); ?></td><td><?php echo htmlspecialchars($r['roll_no']); ?></td><td><?php echo htmlspecialchars($r['event_title']); ?></td><td><?php echo $r['registered_at']; ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</section>

<footer></footer>
</body>
</html>
