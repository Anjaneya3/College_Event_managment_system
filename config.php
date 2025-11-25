<?php
// config.php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';     // set your MySQL root password if any
$DB_NAME = 'college_events';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
session_start();

function isLoggedIn(){
    return isset($_SESSION['user']);
}
function isAdmin(){
    return isLoggedIn() && ($_SESSION['user']['role'] === 'admin');
}
function requireAdmin(){
    if(!isAdmin()){
        header('Location: /college_event_system/admin_login.php');
        exit;
    }
}
function requireLogin(){
    if(!isLoggedIn()){
        header('Location: /college_event_system/login.php');
        exit;
    }
}
?>
