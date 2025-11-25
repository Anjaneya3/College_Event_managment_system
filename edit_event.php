<?php
require '../config.php';
if(!isAdmin()){ header('Location: login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM events WHERE event_id=?");
$stmt->bind_param('i',$id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
if(!$event){ header('Location: dashboard.php'); exit; }

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['event_name']);
    $desc = trim($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = trim($_POST['venue']);
    $imageName = $event['image'];

    if(!empty($_FILES['image']['name'])){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time().rand(100,999).".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$imageName);
    }

    if($name === '') $errors[] = "Event name required";
    if($date === '') $errors[] = "Date required";

    if(empty($errors)){
        $upd = $conn->prepare("UPDATE events SET event_name=?, description=?, date=?, time=?, venue=?, image=? WHERE event_id=?");
        $upd->bind_param('ssssssi',$name,$desc,$date,$time,$venue,$imageName,$id);
        if($upd->execute()){
            header('Location: dashboard.php'); exit;
        } else {
            $errors[] = "Update failed.";
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Edit Event</title>
<link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<?php include '../header.php'; ?>
<main class="container">
  <h2>Edit Event</h2>
  <?php if(!empty($errors)): foreach($errors as $e) echo '<p class="errors">'.htmlspecialchars($e).'</p>'; endforeach; ?>
  <form method="post" enctype="multipart/form-data" class="form">
    <label>Event Name</label><input name="event_name" value="<?=htmlspecialchars($event['event_name'])?>" required>
    <label>Description</label><textarea name="description"><?=htmlspecialchars($event['description'])?></textarea>
    <label>Date</label><input name="date" type="date" value="<?=htmlspecialchars($event['date'])?>" required>
    <label>Time</label><input name="time" type="time" value="<?=htmlspecialchars($event['time'])?>">
    <label>Venue</label><input name="venue" value="<?=htmlspecialchars($event['venue'])?>">
    <?php if(!empty($event['image']) && file_exists('../uploads/'.$event['image'])): ?>
      <p>Current Image:</p>
      <img src="../uploads/<?=htmlspecialchars($event['image'])?>" style="max-width:200px;">
    <?php endif; ?>
    <label>Change Image</label><input name="image" type="file" accept="image/*">
    <button class="btn">Update Event</button>
  </form>
</main>
<?php include '../footer.php'; ?>
</body></html>
