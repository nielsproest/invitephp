<?php
$config = require 'config.php';

$dsn = "pgsql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};user={$config['db_user']};password={$config['db_password']}";

try {
	$pdo = new PDO($dsn);
	$message = "Connected to the database successfully!";
} catch (PDOException $e) {
	$message = "Connection failed: " . $e->getMessage();
	echo $message;
	echo "<br>";
}

$ACCEPT_ENUM = array(
	-1 => "Not answered",
	0 => "Accepted",
	1 => "Declined",
);

$COLOR_ENUM = array(
	-1 => "",
	0 => "accept",
	1 => "delete",
);

function getUrl() {
	return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function initQuery() {
	$url = getUrl();
	$parts = parse_url($url);

	if (!isset($parts['query'])) {
		/*http_response_code(400);
		die("Query not set");*/
	} else {
		parse_str($parts['query'], $query);
	}

	return $query;
}

class Users { 
	static function create($name, $email, $contact) {
		global $pdo;
		$stmt = $pdo->prepare("INSERT INTO Persons (NAME, EMAIL, CONTACT) VALUES (?, ?, ?)");
		$stmt->execute([$name, $email, $contact]);
		return $stmt->fetch();
	}
	static function update($id, $name, $contact) {
		global $pdo;
		$stmt = $pdo->prepare("UPDATE Persons SET NAME=?, CONTACT=? WHERE ID=?");
		$stmt->execute([$name, $contact, $id]);
	}
	static function delete($id) {
		global $pdo;
		$stmt = $pdo->prepare("DELETE FROM Persons WHERE ID=?");
		$stmt->execute([$id]);
	}
	static function find($id) {
		global $pdo;
		$stmt = $pdo->prepare("SELECT * FROM Persons WHERE ID=?");
		$stmt->execute([$id]);
		return $stmt->fetch();
	}
	static function list() {
		global $pdo;
		$stmt = $pdo->query("SELECT * FROM Persons");
		return $stmt->fetchAll();
	}
}

class Events {
	static function create($title, $stop_date) {
		global $pdo;
		$stmt = $pdo->prepare("INSERT INTO Events (TITLE, STOP_DATE) VALUES (?, ?)");
		$stmt->execute([$title, $stop_date]);
		return $stmt->fetch();
	}
	static function update($id, $title, $stop_date) {
		global $pdo;
		$stmt = $pdo->prepare("UPDATE Events SET TITLE=?, STOP_DATE=? WHERE ID=?");
		$stmt->execute([$title, $stop_date, $id]);
	}
	static function delete($id) {
		global $pdo;
		$stmt = $pdo->prepare("DELETE FROM Events WHERE ID=?");
		$stmt->execute([$id]);
	}
	static function list() {
		global $pdo;
		$stmt = $pdo->query("SELECT * FROM Events");
		return $stmt->fetchAll();
	}
	static function find($id) {
		global $pdo;
		$stmt = $pdo->prepare("SELECT * FROM Events WHERE ID=?");
		$stmt->execute([$id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	static function findByPerson($id) {
		global $pdo;
		$stmt = $pdo->prepare("SELECT E.* FROM Invitations i JOIN Events e ON i.EventID=e.ID WHERE i.PersonID=?");
		$stmt->execute([$id]);
		return $stmt->fetchAll();
	}
}

class Invitations {
	function create($event_id, $person_id) {
		global $pdo;
		$stmt = $pdo->prepare("INSERT INTO Invitations (EventID, PersonID) VALUES (?, ?)");
		$stmt->execute([$event_id, $person_id]);
	}
	function update($id, $event_id, $person_id, $accepted) {
		global $pdo;
		$stmt = $pdo->prepare("UPDATE Invitation SET EventID=?, PersonID=?, ACCEPTED=? WHERE ID=?");
		$stmt->execute([$event_id, $person_id, $accepted, $id]);
	}
	function delete($id) {
		global $pdo;
		$stmt = $pdo->prepare("DELETE FROM Invitations WHERE ID=?");
		$stmt->execute([$id]);
	}
	function list() {
		global $pdo;
		$stmt = $pdo->query("SELECT * FROM Invitations");
		return $stmt->fetchAll();
	}
	function find($id) {
		global $pdo;
		$stmt = $pdo->prepare("SELECT * FROM Invitations WHERE ID=?");
		$stmt->execute([$id]);
		return $stmt->fetch();
	}
	function findByPerson($id) {
		global $pdo;
		$stmt = $pdo->prepare("SELECT * FROM Invitations WHERE PersonID=?");
		$stmt->execute([$id]);
		return $stmt->fetchAll();
	}
	function change_status($id, $status) {
		global $pdo;
		$stmt = $pdo->prepare("UPDATE Invitations SET ACCEPTED=? WHERE ID=?");
		$stmt->execute([$status, $id]);
		return $stmt->fetch();
	}
}

function findKey($key) {
	if (strlen($key) == 0) {
		return NULL;
	}

	global $pdo;
	$stmt = $pdo->prepare("SELECT * FROM Keys WHERE ID=?");
	$stmt->execute([$key]);
	return $stmt->fetch();
}

$users = new Users();
$events = new Events();
$invits = new Invitations();


?>