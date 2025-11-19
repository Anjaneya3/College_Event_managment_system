<?php
// register_event.php
session_start();
require 'config.php';
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit;
}
$student_id = $_SESSION['student_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $event_id = (int)$_POST['event_id'];
    // attempt insert
    $stmt = $conn->prepare("INSERT IGNORE INTO registrations (student_id, event_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $event_id);
    if ($stmt->execute()) {
        header("Location: student_dashboard.php");
        exit;
    } else {
        echo "Failed to register: " . $stmt->error;
    }
}
