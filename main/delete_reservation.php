<?php
require_once 'includes/database.php';
require_once 'includes/auth.php';

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        $del_res = $conn->prepare("DELETE FROM reservation_products 
                                 WHERE reservation_id = (SELECT id FROM reservations WHERE id = ? AND customer_id = ?)");
        $del_res->execute([$reservation_id, $user_id]);

        $del_res2 = $conn->prepare("DELETE FROM reservations WHERE id = ? AND customer_id = ?");
        $del_res2->execute([$reservation_id, $user_id]);

        if ($del_res2->rowCount() > 0) {
            header("Location: profile.php?msg=deleted");
        } else {
            die("Fout: Je kunt deze reservering niet verwijderen.");
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
        die("Er ging iets mis bij het verwijderen.");
    }
} else {
    header("Location: profile.php");
}

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete</title>
</head>
<body>

<p> <?= htmlentities($errorMessage) ?> </p>

</body>
</html>

