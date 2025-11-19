<?php
// admin_login.php
session_start();
require 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, password, name FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if ($res && password_verify($password, $res['password'])) {
        $_SESSION['admin_id'] = $res['id'];
        $_SESSION['admin_name'] = $res['name'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $err = "Invalid credentials";
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<header><h2 class="logo">College Event Management</h2></header>
<section class="container">
  <h2 class="center">Admin Login</h2>
  <div class="form-wrap">
    <?php if($err) echo "<p style='color:red'>$err</p>"; if(isset($_GET['registered'])) echo "<p style='color:green'>Registered. Please login.</p>"; ?>
    <form method="post">
      <input name="email" type="email" placeholder="Email" required>
      <input name="password" type="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p>Need an admin account? <a href="admin_register.php">Register</a></p>
  </div>
</section></body></html>
