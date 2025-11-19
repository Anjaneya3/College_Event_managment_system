<?php
require 'config.php';
if (isset($_GET['event_id'])) {
$id = (int)$_GET['event_id'];
$stmt = $conn->prepare("SELECT e.*, a.name as admin_name FROM events e LEFT JOIN admins a ON e.created_by_admin = a.id WHERE e.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();
} else {
$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC")->fetch_all(MYSQLI_ASSOC);
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Events</title><link rel="stylesheet" href="style.css"></head>
<body>
<header><h2 class="logo">College Event Management</h2></header>
<section class="container">
<?php if(isset($event) && $event): ?>
<h2><?php echo htmlspecialchars($event['title']); ?></h2>
<p><?php echo date('d M Y', strtotime($event['event_date'])); ?> <?php echo $event['event_time'] ? ' | '.$event['event_time'] : ''; ?></p>
<p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
<p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
<?php else: ?>
<h2>All Events</h2>
<div class="event-grid">
<?php foreach($events as $e): ?>
<div class="event-card">
<h3><?php echo htmlspecialchars($e['title']); ?></h3>
<p><?php echo date('d M Y', strtotime($e['event_date'])); ?></p>
<a class="small-btn" href="events.php?event_id=<?php echo $e['id']; ?>">View</a>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</section>
</body>
</html>