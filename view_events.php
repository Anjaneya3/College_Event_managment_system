<?php
include 'config.php';

$sql = "SELECT * FROM events ORDER BY date ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Events</title>
</head>
<body>

<h2>All Events</h2>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Description</th>
    <th>Date</th>
    <th>Venue</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['title']; ?></td>
    <td><?php echo $row['description']; ?></td>
    <td><?php echo $row['date']; ?></td>
    <td><?php echo $row['venue']; ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
