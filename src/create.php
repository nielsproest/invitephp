<?php
require 'aux.php';

// TODO: Authentication
if(isset($_POST['type'])) {
	switch ($_POST['type']) {
		case "create_event":
			// Get event details
			$title = $_POST['title'];
			$stop_date = $_POST['stop_date'];

			// Insert into events table
			try {
				$rtn = $events->create($title, $stop_date);
			} catch (PDOException $e) {
				http_response_code(400);
				die("SQL Error");
				break;
			}

			echo "OK";
			http_response_code(200);
			break;
		case "delete_event":
			$id = $_POST['id'];
			
			try {
				$rtn = $events->delete($id);
			} catch (PDOException $e) {
				http_response_code(400);
				die("SQL Error");
				break;
			}

			echo "OK";
			http_response_code(200);
			break;
		case "create_invitation":
			// Get invitations details
			$person_ids = $_POST['person_ids'];
			$event_id = $_POST['event_id'];

			// Insert into invitations table
			try {
				foreach ($person_ids as &$person_id) {
					$rtn = $invits->create($event_id, $person_id);
				}
			} catch (PDOException $e) {
				http_response_code(400);
				die("SQL Error");
				break;
			}
			// TODO: Send mail

			echo "OK";
			http_response_code(200);
			break;
		case "delete_invitation":
			$id = $_POST['id'];
			
			try {
				$rtn = $invits->delete($id);
			} catch (PDOException $e) {
				http_response_code(400);
				die("SQL Error");
				break;
			}

			echo "OK";
			http_response_code(200);
			break;
		case "change_invite_status":
			// Get users details
			$id = $_POST['id'];
			$status = $_POST['status'];

			// Update Invite status
			try {
				$invite = $invits->find($id);
				$event = $events->find($invite["eventid"]);

				$date1=date('d/m/y');
				$tempArr=explode('-', $event["stop_date"]);
				$date2 = date("d/m/y", mktime(0, 0, 0, $tempArr[1], $tempArr[2], $tempArr[0]));

				if ($date1 > $date2) {
					http_response_code(403);
					die("SQL Error");
				}

				$rtn = $invits->change_status($id, $status);
			} catch (PDOException $e) {
				http_response_code(400);
				die("SQL Error");
				break;
			}

			echo "OK";
			http_response_code(200);
			break;
		case "create_user":
			// Get users details
			$name = $_POST['name'];
			$email = $_POST['email'];
			$contact = $_POST['contact'];

			// Insert into users table
			try {
				$rtn = $users->create($name, $email, $contact);
			} catch (PDOException $e) {
				http_response_code(400);
				die("SQL Error");
				break;
			}

			echo "OK";
			http_response_code(200);
			break;
		case "delete_user":
			$id = $_POST['id'];
			
			try {
				$rtn = $users->delete($id);
			} catch (PDOException $e) {
				http_response_code(400);
				die("SQL Error");
				break;
			}

			echo "OK";
			http_response_code(200);
			break;
		default:
			echo "Failed";
			http_response_code(406);
			break;
	}
}
echo "<br>";
echo "Done!";
?>