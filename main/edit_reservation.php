<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success_msg = "";

$res_id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$res_id) {
    header("Location: profile.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = trim($_POST['notes']);
    $selected_products = $_POST['products'] ?? [];

    if (empty($date) || empty($time)) {
        $errors[] = "Datum en tijd zijn verplicht.";
    }
    if (empty($selected_products)) {
        $errors[] = "Kies minimaal één behandeling.";
    }

    if (empty($errors)) {
        try {
            $conn->beginTransaction();

            $t = $conn->prepare("UPDATE reservations SET date = ?, time = ?, notes = ? WHERE id = ? AND customer_id = ?");
            $t->execute([$date, $time, $notes, $res_id, $user_id]);

            if ($t->rowCount() == 0) {
                $check = $conn->prepare("SELECT id FROM reservations WHERE id = ? AND customer_id = ?");
                $check->execute([$res_id, $user_id]);
                if ($check->rowCount() == 0) {
                    throw new Exception("Je mag deze reservering niet wijzigen.");
                }
            }

            $del = $conn->prepare("DELETE FROM reservation_products WHERE reservation_id = ?");
            $del->execute([$res_id]);

            $ins = $conn->prepare("INSERT INTO reservation_products (reservation_id, product_id) VALUES (?, ?)");
            foreach ($selected_products as $prod_id) {
                $ins->execute([$res_id, $prod_id]);
            }

            $conn->commit();
            $success_msg = "Afspraak succesvol gewijzigd!";
            header("refresh:1;url=profile.php");

        } catch (Exception $e) {
            $conn->rollBack();
            error_log($e->getMessage());
            $errors[] = "Er ging iets mis: " . $e->getMessage();
        }
    }
}

$t = $conn->prepare("SELECT * FROM reservations WHERE id = ? AND customer_id = ?");
$t->execute([$res_id, $user_id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die("Reservering niet gevonden of geen toegang.");
}

$t_prods = $conn->prepare("SELECT product_id FROM reservation_products WHERE reservation_id = ?");
$t_prods->execute([$res_id]);

$current_product_ids = $t_prods->fetchAll(PDO::FETCH_COLUMN);

$t_all = $conn->query("SELECT * FROM products");
$all_products = $t_all->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wijzig Afspraak - GRILLZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> .text-gold { color: #D4AF37; } .bg-gold { background-color: #D4AF37; } </style>
</head>
<body class="bg-gray-100 font-sans py-8">

<div class="container mx-auto px-4 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-4">Afspraak Wijzigen</h1>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach($errors as $error) { echo "<p>⚠️ $error</p>"; } ?>
            </div>
        <?php endif; ?>

        <?php if ($success_msg): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $success_msg ?> <br><span class="text-sm">Je wordt teruggestuurd...</span>
            </div>
        <?php endif; ?>

        <form action="edit_reservation.php" method="POST">
            <input type="hidden" name="id" value="<?= $reservation['id'] ?>">

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Datum</label>
                    <input type="date" name="date"
                           value="<?= $reservation['date'] ?>"
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tijd</label>
                    <input type="time" name="time"
                           value="<?= substr($reservation['time'], 0, 5)?>"
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Behandeling(en) aanpassen</label>
                <div class="bg-gray-50 p-4 rounded border h-48 overflow-y-scroll">
                    <?php foreach($all_products as $prod): ?>
                        <?php
                        $is_checked = in_array($prod['id'], $current_product_ids) ? 'checked' : '';
                        ?>
                        <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-200 p-2 rounded">
                            <input type="checkbox" name="products[]" value="<?= $prod['id'] ?>"
                                   class="form-checkbox h-5 w-5 text-yellow-600" <?= $is_checked ?>>
                            <span class="ml-3 text-gray-800 font-medium"><?= htmlspecialchars($prod['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Opmerkingen</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500"><?= htmlspecialchars($reservation['notes']) ?></textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="profile.php" class="text-gray-500 hover:text-gray-800">Annuleren</a>
                <button type="submit" class="bg-gray-900 text-gold font-bold py-3 px-6 rounded shadow hover:bg-gray-800 transition">
                    Wijzigingen Opslaan
                </button>
            </div>

        </form>
    </div>
</div>

</body>
</html>