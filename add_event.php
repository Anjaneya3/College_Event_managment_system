<?php
require '../config.php';
requireAdmin();
require '../header.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['event_name']);
    $desc = trim($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = trim($_POST['venue']);
    $imageName = '';

    if(!empty($_FILES['image']['name'])){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time().rand(100,999).".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$imageName);
    }

    if($name === '') $errors[] = "Event name required";
    if($date === '') $errors[] = "Date required";

    if(empty($errors)){
        $ins = $conn->prepare("INSERT INTO events (event_name,description,date,time,venue,image) VALUES (?,?,?,?,?,?)");
        $ins->bind_param('ssssss', $name, $desc, $date, $time, $venue, $imageName);
        if($ins->execute()){
            $_SESSION['flash'] = "Event added.";
            header('Location: dashboard.php'); exit;
        } else $errors[] = "Insert failed: ".$conn->error;
    }
}
?>
<main class="container">
  <h2>Add Event</h2>
  <?php if(!empty($errors)) foreach($errors as $e) echo '<p class="errors">'.htmlspecialchars($e).'</p>'; ?>
  <form method="post" enctype="multipart/form-data" class="form">
    <label>Event Name</label><input name="event_name" required>
    <label>Description</label><textarea name="description"></textarea>
    <label>Date</label><input name="date" type="date" required>
    <label>Time</label><input name="time" type="time">
    <label>Venue</label><input name="venue">
    <label>Image</label><input name="image" type="file" accept="image/*">
    <button class="btn">Add Event</button>
  </form>
</main>
<?php require '../footer.php'; ?>
