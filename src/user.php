<?php
require 'aux.php';

$query = initQuery();

$user_id = isset($query['id']) ? $query['id'] : '';

$user = $users->find($user_id);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User page</title>

	<link rel="stylesheet" type="text/css" href="/static/water.css">
	<link rel="stylesheet" type="text/css" href="/static/style.css">
</head>
<body>

<h1>User: <?php echo $user["name"] ?></h1>

<div class="background-container">
	<h1>Invitations</h1>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Event</th>
				<th>ACCEPTED</th>
			</tr>
		</thead>
		<tbody>
			<?php

			foreach ($invits->list() as &$row) {
				$event = $events->find($row['eventid']);
				$person = $users->find($row['personid']);

				echo "<tr>";
				echo "<td>" . $row['id'] . "</td>";
				echo "<td>" . $event['title'] . " (" . $event['id'] . ")</td>";
				echo "<td>" . $person['name'] . " (" . $person['id'] . ")</td>";
				echo "<td>" . $ACCEPT_ENUM[$row['accepted']] . "</td>";
				echo "<td><a href='/edit_user.php?id=" . $row['id'] . "'>Edit</a> <a class='delete' href='#'>Delete</a></td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>

</body>
</html>