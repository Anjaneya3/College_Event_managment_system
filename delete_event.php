<?php
require '../config.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
if($id){
    // optionally remove image file
    $stmt = $conn->prepare("SELECT image FROM events WHERE event_id=?");
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if($row && !empty($row['image']) && file_exists('../uploads/'.$row['image'])){
        @unlink('../uploads/'.$row['image']);
    }

    $del = $conn->prepare("DELETE FROM events WHERE event_id=?");
    $del->bind_param('i',$id);
    $del->execute();
    $_SESSION['flash'] = "Event deleted.";
}
header('Location: dashboard.php'); exit;
