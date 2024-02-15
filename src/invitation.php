<?php
require 'aux.php';

$query = initQuery();

$invitation_id = isset($query['id']) ? $query['id'] : '';

// TODO: Catch errors
$invit = $invits->find($invitation_id);

$user = $users->find($invit["personid"]);
$event = $events->find($invit["eventid"]);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Invitation page</title>

	<link rel="stylesheet" type="text/css" href="/static/water.css">
	<link rel="stylesheet" type="text/css" href="/static/style.css">
</head>
<body>


<div class="background-container">
	<h1>Invitation</h1>
	<h2>User: <?php echo $user["name"] ?></h2>
	<h2>Cutoff date: <?php echo $event["stop_date"] ?></h2>
	<table>
		<thead>
			<tr>
				<th>Event</th>
				<th>ACCEPTED</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo "<tr>";
			echo "<td>" . $event['title'] . " (" . $event['id'] . ")</td>";
			echo "<td class='" . $COLOR_ENUM[$invit['accepted']] . "'>" . $ACCEPT_ENUM[$invit['accepted']] . "</td>";
			echo "<td>";
			echo "<a class='accept' onclick='submit(0)' href='#'>Accept</a> ";
			echo "<a class='delete' onclick='submit(1)' href='#'>Decline</a>";
			echo "</td>";
			echo "</tr>";
			?>
		</tbody>
	</table>
	<div id="error-message" style="color: red;"></div>
</div>

<script>
	function submit(val) {
		// Use FormData to gather form data
		var formData = new FormData();

		formData.append('id', '<?php echo $invitation_id ?>');
		formData.append('type', "change_invite_status");
		formData.append('status', val.toString());

		// Use Fetch API to send form data asynchronously
		fetch("/create.php", {
			method: 'POST',
			body: formData
		})
		.then(response => {
			if (!response.ok) {
				throw new Error('Network response was not ok');
			}
			return response
		})
		.then(data => {
			// Handle the result data here
			console.log(data);
			document.getElementById("error-message").innerHTML = "";
			window.location.reload();
		})
		.catch(error => {
			// Handle any errors that occurred during the fetch
			console.error('Error:', error);
			document.getElementById("error-message").innerHTML = "Error changing status. Please try again.";
		});
	}
</script>

</body>
</html>