<?php
require 'aux.php';

$request = $_SERVER['REQUEST_URI'];
/*
switch ($request) {
	case '/views/users':
		require __DIR__ . '/views/users.php';
	case '/views/department':
		require __DIR__ . '/views/dep.php';
	default:
		http_response_code(404);
		require __DIR__ . $viewDir . '404.php';
}
*/
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>PHP PostgreSQL</title>
	</head>
	<body>
		<h1>PHP PostgreSQL</h1>
		<p><?php echo $message; ?></p>
		<?php
		$path = "/var/www/html";
		$files = array_diff(scandir($path), array('..', '.'));
		foreach ($files as &$row) {
			echo "<a href='/$row'>" . $row . "</a><br>";
		}
		?>
	</body>
</html>