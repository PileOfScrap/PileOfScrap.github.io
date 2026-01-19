<?php

$host = '127.0.0.1';
$user = 'root';
$password = '';
$database = 'grillaz';

require_once 'includes/auth.php';

$db = mysqli_connect($host, $user, $password, $database);

$errorMessage = '';

if (!isset($_GET['id']) || $_GET['id'] == '') {
    header('Location: index.php');
    exit;
}

$id = mysqli_escape_string($db, $_GET['id']);

$query = "DELETE FROM reservations WHERE id = '$id'";
$result = mysqli_query($db, $query);

if ($result) {
    header('Location: index.php');
    exit;
} else {
    $errorMessage = 'Er ging iets mis bij het verwijderen';
}

mysqli_close($db);
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete</title>
</head>
<body>

<p><?= htmlentities($errorMessage) ?></p>

</body>
</html>
