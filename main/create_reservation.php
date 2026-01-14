<?php
require_once 'database.php';
require_once 'includes/auth.php';

$errors = [];

$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = trim($_POST['notes']);
    $selected_products = $_POST['products'] ?? [];

    if (empty($date) || empty($time)) {
        $errors[] = "Datum en tijd zijn verplicht.";
    }
    if (empty($selected_products)) {
        $errors[] = "Kies minimaal één product/behandeling.";
    }

    if (empty($errors)) {
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO reservations (customer_id, date, time, notes) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $date, $time, $notes]);

            $reservation_id = $conn->lastInsertId();

            $stmt_product = $conn->prepare("INSERT INTO reservation_products (reservation_id, product_id) VALUES (?, ?)");

            foreach ($selected_products as $product_id) {
                $stmt_product->execute([$reservation_id, $product_id]);
            }

            $conn->commit();

            header("Location: profile.php");
            exit;

        } catch (Exception $e) {
            $conn->rollBack();
            error_log($e->getMessage());
            $errors[] = "Er ging iets mis met opslaan. Probeer het opnieuw.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Nieuwe Afspraak - GRILLAZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> .text-gold { color: #D4AF37; } .bg-gold { background-color: #D4AF37; } </style>
</head>
<body class="bg-gray-100 font-sans">

<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-4">Nieuwe Afspraak</h1>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach($errors as $error) { echo "<p>⚠️ $error</p>"; } ?>
            </div>
        <?php endif; ?>

        <form action="create_reservation.php" method="POST">

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Datum</label>
                    <input type="date" name="date" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tijd</label>
                    <input type="time" name="time" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Kies je behandeling(en)</label>
                <div class="bg-gray-50 p-4 rounded border h-48 overflow-y-scroll">
                    <?php if(count($products) > 0): ?>
                        <?php foreach($products as $prod): ?>
                            <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-200 p-2 rounded">
                                <input type="checkbox" name="products[]" value="<?= $prod['id'] ?>" class="form-checkbox h-5 w-5 text-yellow-600">
                                <span class="ml-3 text-gray-800 font-medium"><?= htmlspecialchars($prod['name']) ?></span>
                                <span class="ml-auto text-gray-500">€ <?= $prod['price'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-red-500 italic">Geen producten gevonden in de database. Voeg deze eerst toe via phpMyAdmin!</p>
                    <?php endif; ?>
                </div>
                <p class="text-xs text-gray-500 mt-1">Je kunt meerdere opties selecteren.</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Opmerkingen (Bijv. specifieke wensen)</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500"></textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="profile.php" class="text-gray-500 hover:text-gray-800">Annuleren</a>
                <button type="submit" class="bg-gray-900 text-gold font-bold py-3 px-6 rounded shadow hover:bg-gray-800 transition">
                    Afspraak Bevestigen
                </button>
            </div>

        </form>
    </div>
</div>

</body>

</html>

