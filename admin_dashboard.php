<?php
require '../config.php';
requireAdmin();
require '../header.php';

// fetch events
$res = $conn->query("SELECT * FROM events ORDER BY date ASC");
$events = $res->fetch_all(MYSQLI_ASSOC);

// handle flash
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<main class="container">
  <h2>Admin Dashboard</h2>
  <?php if($flash) echo '<p class="info">'.htmlspecialchars($flash).'</p>'; ?>
  <p><a class="btn" href="add_event.php">Add Event</a></p>

  <h3>Events</h3>
  <table class="table">
    <thead><tr><th>ID</th><th>Event</th><th>Date</th><th>Venue</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach($events as $e): ?>
      <tr>
        <td><?= $e['event_id'] ?></td>
        <td><?= htmlspecialchars($e['event_name']) ?></td>
        <td><?= htmlspecialchars($e['date']) ?></td>
        <td><?= htmlspecialchars($e['venue']) ?></td>
        <td>
          <a href="edit_event.php?id=<?=$e['event_id']?>">Edit</a> |
          <a href="delete_event.php?id=<?=$e['event_id']?>" onclick="return confirm('Delete event?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h3>Registrations</h3>
  <?php
  $rres = $conn->query("SELECT r.reg_id, u.name as student, e.event_name, r.registered_on FROM registrations r JOIN users u ON r.user_id=u.user_id JOIN events e ON r.event_id=e.event_id ORDER BY r.registered_on DESC");
  $regs = $rres->fetch_all(MYSQLI_ASSOC);
  ?>
  <table class="table">
    <thead><tr><th>#</th><th>Student</th><th>Event</th><th>When</th></tr></thead>
    <tbody>
      <?php foreach($regs as $rg): ?>
      <tr>
        <td><?= $rg['reg_id'] ?></td>
        <td><?= htmlspecialchars($rg['student']) ?></td>
        <td><?= htmlspecialchars($rg['event_name']) ?></td>
        <td><?= htmlspecialchars($rg['registered_on']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</main>
<?php require '../footer.php'; ?>
