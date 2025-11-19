<?php
require 'config.php';
$errors = [];  // FIXED: initialize errors array

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $roll = trim($_POST['roll_no']);
    $section = trim($_POST['section']);
    $year = trim($_POST['study_year']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$name || !$roll || !$email || !$password) {
        $errors[] = "Name, roll number, email and password are required.";
    }

    if (empty($errors)) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO students (name, roll_no, section, study_year, phone, email, password)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            die("SQL ERROR: " . $conn->error);
        }

        $stmt->bind_param("sssssss", $name, $roll, $section, $year, $phone, $email, $hash);

        if ($stmt->execute()) {
            header("Location: student_login.php?registered=1");
            exit;
        } else {
            $errors[] = "Registration failed: " . $stmt->error;
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
<h2 class="logo">College Event Management</h2>
</header>

<section class="container">
<h2 class="center">Student Registration</h2>

<div class="form-wrap">
<?php
if (!empty($errors)) {
    foreach ($errors as $e) {
        echo "<p style='color:red'>" . htmlspecialchars($e) . "</p>";
    }
}
?>

<form method="post">
    <input name="name" placeholder="Full name" required>
    <input name="roll_no" placeholder="Roll number" required>

    <div class="form-row">
        <div class="col"><input name="section" placeholder="Section (eg: A)"></div>
        <div class="col"><input name="study_year" placeholder="Study year (eg: 3rd)"></div>
    </div>

    <input name="phone" placeholder="Phone number">
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password (min 6 char)" required>

    <button type="submit">Register</button>

    <p>Already registered? <a href="student_login.php">Login here</a></p>
</form>

</div>
</section>

<footer><p>© <?php echo date('Y'); ?></p></footer>

</body>
</html>
