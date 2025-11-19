<?php
// admin_register.php
require 'config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']); $phone = trim($_POST['phone']); $email = trim($_POST['email']); $role = trim($_POST['role']); $password = $_POST['password'];
    if (!$name || !$email || !$password) $errors[] = "Name, email and password required.";
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admins (name, phone, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $phone, $email, $hash, $role);
        if ($stmt->execute()) {
            header("Location: admin_login.php?registered=1");
            exit;
        } else $errors[] = $stmt->error;
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Register</title><link rel="stylesheet" href="style.css"></head>
<body>
<header><h2 class="logo">College Event Management</h2></header>
<section class="container">
  <h2 class="center">Admin Registration</h2>
  <div class="form-wrap">
    <?php foreach($errors as $e) echo "<p style='color:red'>".htmlspecialchars($e)."</p>"; ?>
    <form method="post">
      <input name="name" placeholder="Admin name" required>
      <input name="phone" placeholder="Phone">
      <input name="email" placeholder="Email" required>
      <input name="role" placeholder="Role (which event incharge)">
      <input name="password" type="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>
  </div>
</section>
<footer></footer></body>
</html>
