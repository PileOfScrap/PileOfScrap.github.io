<?php
require_once 'database.php';
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

