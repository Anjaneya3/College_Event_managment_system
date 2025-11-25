<?php
require 'config.php';

// Fetch events from DB
$events = [];
$sql = "SELECT * FROM events ORDER BY event_date ASC LIMIT 6";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Event Management</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body { 
            margin: 0; 
            font-family: Arial, sans-serif; 
            background: #f8f8f8;
        }

        header {
            background: #0d47a1;
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        nav a:hover {
            color: yellow;
        }

        .hero {
            text-align: center;
            padding: 40px;
            background: white;
        }

        .hero h1 { font-size: 36px; margin: 0; }
        .hero p { font-size: 18px; margin-top: 10px; }

        .btn {
            padding: 10px 20px;
            background: #0d47a1;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin: 5px;
        }

        .event-grid {
            width: 80%;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 30px 0;
        }

        .event-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            text-align: center;
        }

        footer {
            background: #0d47a1;
            padding: 15px;
            color: white;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>

<header>
    <nav>
        <a href="index.php">Home</a>
        <a href="events.php">Events</a>
        <a href="admin_login.php">Admin</a>
        <a href="student_login.php">Student</a>
    </nav>
</header>

<section class="hero">
    <h1>Welcome to College Event Management</h1>
    <p>Register, manage, and explore events easily.</p>

    <a class="btn" href="student_register.php">Student Sign Up</a>
    <a class="btn" href="student_login.php">Student Login</a>
    <a class="btn" href="admin_login.php">Admin Login</a>
</section>

<h2 style="text-align:center;">Upcoming Events</h2>

<?php if (count($events) === 0): ?>
    <p style="text-align:center;">No events found.</p>
<?php else: ?>
<div class="event-grid">
    <?php foreach($events as $e): ?>
    <div class="event-card">
        <h3><?= htmlspecialchars($e['title']) ?></h3>
        <p><?= date('d M Y', strtotime($e['event_date'])) ?></p>
        <a class="btn" href="events.php?event_id=<?= $e['id'] ?>">Details</a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<footer>
    &copy; <?= date('Y'); ?> College Event Management System
</footer>

</body>
</html>
