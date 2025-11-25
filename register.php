<?php
require 'config.php';
require 'header.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if($name === '') $errors[] = "Name required";
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required";
    if(strlen($password) < 6) $errors[] = "Password must be at least 6 characters";

    if(empty($errors)){
        $chk = $conn->prepare("SELECT user_id FROM users WHERE email=?");
        $chk->bind_param('s',$email);
        $chk->execute();
        if($chk->get_result()->num_rows > 0){
            $errors[] = "Email already registered";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?, 'student')");
            $ins->bind_param('sss',$name,$email,$hash);
            if($ins->execute()){
                header('Location: login.php?registered=1'); exit;
            } else {
                $errors[] = "Registration failed: " . $conn->error;
            }
        }
    }
}
?>
<main class="container">
  <h2>Student Register</h2>
  <?php if(!empty($errors)){ foreach($errors as $e) echo '<p class="errors">'.htmlspecialchars($e).'</p>'; } ?>
  <form method="post" class="form">
    <label>Name</label><input name="name" required>
    <label>Email</label><input name="email" type="email" required>
    <label>Password</label><input name="password" type="password" required>
    <button class="btn">Register</button>
  </form>
</main>
<?php require 'footer.php'; ?>
