<?php
require 'config.php';
require 'header.php';

if(isset($_GET['id'])){
  $id = (int)$_GET['id'];
  $stmt = $conn->prepare("SELECT * FROM events WHERE event_id=?");
  $stmt->bind_param('i',$id);
  $stmt->execute();
  $event = $stmt->get_result()->fetch_assoc();
  if(!$event){
    echo "<main class='container'><p>Event not found.</p></main>";
    require 'footer.php'; exit;
  }
  ?>
  <main class="container">
    <h2><?=htmlspecialchars($event['event_name'])?></h2>
    <?php if(!empty($event['image']) && file_exists('uploads/'.$event['image'])): ?>
      <img src="uploads/<?=htmlspecialchars($event['image'])?>" class="event-large">
    <?php endif; ?>
    <p><?=nl2br(htmlspecialchars($event['description']))?></p>
    <p><strong>Date:</strong> <?=date('d M Y', strtotime($event['date']))?> &nbsp; <strong>Venue:</strong> <?=htmlspecialchars($event['venue'])?></p>

    <?php if(isLoggedIn() && $_SESSION['user']['role']==='student'): ?>
      <form method="post" action="event_register.php">
        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
        <button class="btn" type="submit">Register for Event</button>
      </form>
    <?php elseif(!isLoggedIn()): ?>
      <p><a href="login.php">Login</a> or <a href="register.php">Register</a> to join.</p>
    <?php endif; ?>
  </main>
  <?php
  require 'footer.php';
  exit;
}

// otherwise list all events
$stmt = $conn->prepare("SELECT * FROM events ORDER BY date ASC");
$stmt->execute();
$events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<main class="container">
  <h2>All Events</h2>
  <div class="event-grid">
    <?php foreach($events as $e): ?>
      <div class="event-card">
        <?php if(!empty($e['image']) && file_exists('uploads/'.$e['image'])): ?>
          <img src="uploads/<?=htmlspecialchars($e['image'])?>" class="event-thumb">
        <?php endif; ?>
        <h3><?=htmlspecialchars($e['event_name'])?></h3>
        <p class="meta"><?=date('d M Y', strtotime($e['date']))?> | <?=htmlspecialchars($e['venue'])?></p>
        <a class="small-btn" href="events.php?id=<?= $e['event_id'] ?>">Details</a>
      </div>
    <?php endforeach; ?>
  </div>
</main>
<?php require 'footer.php'; ?>
