<?php
session_start();
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // validation
    if (!$email || !$password) {
        $errors[] = "Email and password are required.";
    }

    if (empty($errors)) {
        // Check student exists
        $stmt = $conn->prepare("SELECT id, name, password FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $student = $result->fetch_assoc();

            // verify password
            if (password_verify($password, $student['password'])) {

                // store session
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_name ='] = $student['name'];

                header("Location: student_dashboard.php");
                exit;

            } else {
                $errors[] = "Incorrect password!";
            }

        } else {
            $errors[] = "No student found with this email.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h2 class="logo">College Event Management</h2>
</header>

<section class="container">
<h2 class="center">Student Login</h2>

<div class="form-wrap">

<?php 
if (!empty($errors)) {
    foreach ($errors as $e) {
        echo "<p style='color:red'>" . htmlspecialchars($e) . "</p>";
    }
}
?>

<form method="post">

    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Login</button>

    <p>Not registered? <a href="student_register.php">Create an account</a></p>

</form>

</div>
</section>

<footer>
    <p>© <?php echo date('Y'); ?></p>
</footer>

</body>
</html>
