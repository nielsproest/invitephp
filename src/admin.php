<?php
require 'aux.php';

$query = initQuery();

$pass_key = isset($query['key']) ? $query['key'] : '';

function fail() {
	/*
	http_response_code(401);
	die("Invalid key");
	*/
}

try {
	if (!findKey($pass_key)) {
		fail();
	}
} catch (PDOException $e) {
	fail();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin page</title>

	<link rel="stylesheet" type="text/css" href="/static/water.css">
	<link rel="stylesheet" type="text/css" href="/static/style.css">
</head>
<body>

<div class="background-container">
	<h1>Events</h1>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>TITLE</th>
				<th>CUTOFF DATE</th>
				<th>ACTION</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($events->list() as &$row) {
				echo "<tr>";
				echo "<td>" . $row['id'] . "</td>";
				echo "<td>" . $row['title'] . "</td>";
				echo "<td>" . $row['stop_date'] . "</td>";
				echo "<td><a href='/edit_event.php?id=" . $row['id'] . "'>Edit</a> <a class='delete'  onclick='submitEventDelete($row[id])' href='#'>Delete</a></td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>

<div class="background-container">
	<h1>Invitations</h1>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Event</th>
				<th>Person</th>
				<th>ACCEPTED</th>
				<th>ACTION</th>
			</tr>
		</thead>
		<tbody>
			<?php

			foreach ($invits->list() as &$row) {
				$event = $events->find($row['eventid']);
				$person = $users->find($row['personid']);

				echo "<tr>";
				echo "<td><a target='_blank' href='/invitation.php?id=$row[id]'>" . $row['id'] . "</a></td>";
				echo "<td>" . $event['title'] . " (" . $event['id'] . ")</td>";
				echo "<td>" . $person['name'] . " (" . $person['id'] . ")</td>";
				echo "<td class='" . $COLOR_ENUM[$row['accepted']] . "'>" . $ACCEPT_ENUM[$row['accepted']] . "</td>";
				echo "<td><a href='/edit_invitation.php?id=" . $row['id'] . "'>Edit</a> <a class='delete' onclick='submitInviteDelete($row[id])' href='#'>Delete</a></td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>

<div class="background-container">
	<h1>Users</h1>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>NAME</th>
				<th>EMAIL</th>
				<th>CONTACT</th>
				<th>ACTION</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($users->list() as &$row) {
				echo "<tr>";
				echo "<td>" . $row['id'] . "</td>";
				echo "<td><a target='_blank' href='/user.php?id=$row[id]'>" . $row['name'] . "</a></td>";
				echo "<td>" . $row['email'] . "</td>";
				echo "<td>" . $row['contact'] . "</td>";
				echo "<td><a href='/edit_user.php?id=" . $row['id'] . "'>Edit</a> <a class='delete' onclick='submitUserDelete($row[id])' href='#'>Delete</a></td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>

<div class="background-container">
<!-- To create new events, invitations, and users -->
<h1>Create Event</h1>
<form id="createEventForm" action="create.php" method="post">
	<label for="title">Title:</label>
	<input type="text" name="title" id="title"><br><br>
	<label for="stop_date">Stop Date:</label>
	<input type="date" name="stop_date" id="stop_date"><br><br>
	<label for="description">Description:</label>
	<textarea name="description" id="description"></textarea><br><br>
	<input type="hidden" name="type" value="create_event">
	<button type="button" onclick="submitEventForm()">Create</button>
	<div id="error-message-event" style="color: red;"></div>
</form>

<script>
	function submitEventDelete(id) {
		
		var formData = new FormData();

		formData.append('type', "delete_event");
		formData.append('id', id.toString());
		
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
			document.getElementById("error-message-event").innerHTML = "";
			window.location.reload();
		})
		.catch(error => {
			// Handle any errors that occurred during the fetch
			console.error('Error:', error);
			document.getElementById("error-message-event").innerHTML = "Error creating event. Please try again.";
		});
	}
	function submitEventForm() {
		// Get the form element
		var form = document.getElementById("createEventForm");

		// Use FormData to gather form data
		var formData = new FormData(form);

		// Use Fetch API to send form data asynchronously
		fetch(form.action, {
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
			document.getElementById("error-message-event").innerHTML = "";
			window.location.reload();
		})
		.catch(error => {
			// Handle any errors that occurred during the fetch
			console.error('Error:', error);
			document.getElementById("error-message-event").innerHTML = "Error creating event. Please try again.";
		});
	}
</script>
</div>

<div class="background-container">
<h1>Create User</h1>
<form id="createUserForm" action="create.php" method="post">
	<label for="name">Name:</label>
	<input type="text" name="name" id="name"><br><br>
	<label for="email">Email:</label>
	<input type="text" name="email" id="email"><br><br>
	<label for="contact">Contact:</label>
	<textarea name="contact" id="contact"></textarea><br><br>
	<input type="hidden" name="type" value="create_user">
	<button type="button" onclick="submitUserForm()">Create</button>
	<div id="error-message-user" style="color: red;"></div>
</form>

<script>
	function submitUserDelete(id) {
		
		var formData = new FormData();

		formData.append('type', "delete_user");
		formData.append('id', id.toString());
		
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
			document.getElementById("error-message-event").innerHTML = "";
			window.location.reload();
		})
		.catch(error => {
			// Handle any errors that occurred during the fetch
			console.error('Error:', error);
			document.getElementById("error-message-event").innerHTML = "Error creating event. Please try again.";
		});
	}
	function submitUserForm() {
		// Get the form element
		var form = document.getElementById("createUserForm");

		// Use FormData to gather form data
		var formData = new FormData(form);

		// Use Fetch API to send form data asynchronously
		fetch(form.action, {
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
			document.getElementById("error-message-user").innerHTML = "";
			window.location.reload();
		})
		.catch(error => {
			// Handle any errors that occurred during the fetch
			console.error('Error:', error);
			document.getElementById("error-message-user").innerHTML = "Error creating user. Please try again.";
		});
	}
</script>
</div>

<div class="background-container">
<h1>Create Invitation</h1>

<form id="createInvitForm" action="create.php" method="post">
	<label for="person_id">Person:</label>
	<select name="person_ids[]" id="person_ids" multiple>
		<?php
		foreach ($users->list() as &$row) {
			echo "<option value='$row[id]'>$row[name] ($row[id])</option>";
		}
		?>
	</select><br><br>
	<label for="event_id">EventID:</label>
	<select name="event_id" id="event_id">
		<?php
		foreach ($events->list() as &$row) {
			echo "<option value='$row[id]'>$row[title] ($row[id])</option>";
		}
		?>
	</select><br><br>
	<input type="hidden" name="type" value="create_invitation">
	<button type="button" onclick="submitInvitForm()">Create</button>
	<div id="error-message-invit" style="color: red;"></div>
</form>

<script>
	function submitInviteDelete(id) {
		
		var formData = new FormData();

		formData.append('type', "delete_invitation");
		formData.append('id', id.toString());
		
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
			document.getElementById("error-message-event").innerHTML = "";
			window.location.reload();
		})
		.catch(error => {
			// Handle any errors that occurred during the fetch
			console.error('Error:', error);
			document.getElementById("error-message-event").innerHTML = "Error creating event. Please try again.";
		});
	}

	function submitInvitForm() {
		// Get the form element
		var form = document.getElementById("createInvitForm");

		// Use FormData to gather form data
		var formData = new FormData(form);

		// Use Fetch API to send form data asynchronously
		fetch(form.action, {
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
			document.getElementById("error-message-invit").innerHTML = "";
			window.location.reload();
		})
		.catch(error => {
			// Handle any errors that occurred during the fetch
			console.error('Error:', error);
			document.getElementById("error-message-invit").innerHTML = "Error creating invitation. Please try again.";
		});
	}
</script>
</div>

</body>
</html>