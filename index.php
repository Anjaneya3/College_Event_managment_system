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

    <style>
        body { 
            margin: 0; 
            font-family: Arial, sans-serif; 
            background: #f8f8f8;
        }

        /* Header */
        header {
            background: #0d47a1;
            color: white;
            padding: 20px 0;     /* 🔥 Increased header height */
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 25px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        nav a:hover {
            color: yellow;
        }

        /* Image Slider */
        .slider {
            width: 100%;
            height: 350px;
            overflow: hidden;
            margin-top: 10px;
            border-radius: 10px;
        }
        .slides {
            width: 300%;
            height: 100%;
            display: flex;
            animation: slideAnimation 12s infinite;
        }
        .slide {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        @keyframes slideAnimation {
            0% { margin-left: 0; }
            33% { margin-left: -100%; }
            66% { margin-left: -200%; }
            100% { margin-left: 0; }
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 40px 0;
            background: white;
        }
        .hero h1 {
            margin: 0;
            font-size: 36px;
        }
        .hero p {
            font-size: 18px;
            margin-top: 10px;
        }
        .btn {
            padding: 10px 20px;
            color: white;
            background: #0d47a1;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-right: 10px;
        }
        .btn.alt {
            background: #424242;
        }

        /* Events */
        .container {
            width: 80%;
            margin: auto;
            padding: 40px 0;
        }
        .event-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .event-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            text-align: center;
        }
        .small-btn {
            display: inline-block;
            padding: 8px 15px;
            background: #0d47a1;
            color: white;
            text-decoration: none;
            margin-top: 10px;
            border-radius: 5px;
        }

        footer {
            background: #0d47a1;
            padding: 20px;
            text-align: center;
            color: white;
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
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>

<!-- 🔥 Image Slider Added -->
<div class="slider">
    <div class="slides">
        <img src="images/slider1.jpg" class="slide">
        <img src="images/slider2.jpg" class="slide">
        <img src="images/slider3.jpg" class="slide">
    </div>
</div>

<section class="hero">
    <h1>Welcome Students & Admins</h1>
    <p>Manage, Register & Stay Updated About College Events</p>
    <a class="btn" href="student_register.php">Student Sign Up</a>
    <a class="btn" href="student_login.php">Student Login</a>
    <a class="btn alt" href="admin_login.php">Admin Login</a>
</section>

<section class="container">
    <h2>Upcoming Events</h2>

    <?php if (count($events) === 0): ?>
        <p>No upcoming events yet.</p>
    <?php else: ?>
    <div class="event-grid">
        <?php foreach($events as $e): ?>
        <div class="event-card">
            <h3><?= htmlspecialchars($e['title']) ?></h3>
            <p><?= date('d M Y', strtotime($e['event_date'])) ?></p>
            <a class="small-btn" href="events.php?event_id=<?= $e['id'] ?>">Details</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<footer>
    <p>© <?= date('Y') ?> College Event Management System</p>
</footer>

</body>
</html>
