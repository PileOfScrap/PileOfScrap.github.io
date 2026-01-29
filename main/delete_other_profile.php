<?php
require_once 'includes/database.php';
require_once 'includes/adminauth.php';

// 1. Check of er een ID is
if (!isset($_GET['id']) || $_GET['id'] == '') {
    header('Location: admin_interface.php');
    exit;
}

$id = $_GET['id'];

try {
    // 2. Start de transactie (alles-of-niets modus)
    $conn->beginTransaction();

    // STAP A: Verwijder eerst de producten gekoppeld aan de reserveringen van deze klant
    // We selecteren alle reservering-ID's van deze klant en verwijderen de items uit reservation_products
    $sql_products = "DELETE FROM reservation_products 
                     WHERE reservation_id IN (SELECT id FROM reservations WHERE customer_id = :id)";
    $stmt_products = $conn->prepare($sql_products);
    $stmt_products->execute([':id' => $id]);

    // STAP B: Verwijder nu de reserveringen zelf
    $sql_res = "DELETE FROM reservations WHERE customer_id = :id";
    $stmt_res = $conn->prepare($sql_res);
    $stmt_res->execute([':id' => $id]);

    // STAP C: Verwijder als laatste de klant
    $sql_user = "DELETE FROM customers WHERE id = :id";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->execute([':id' => $id]);

    // 3. Als alles goed ging: Definitief maken (Commit)
    $conn->commit();

    // Terug naar admin interface
    header('Location: admin_interface.php');
    exit;

} catch (PDOException $e) {
    // 4. Als er iets fout ging: Draai alles terug (Rollback)
    // Zo raak je geen reserveringen kwijt als de klant niet verwijderd kon worden
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    $errorMessage = 'Fout bij verwijderen data: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Fout bij verwijderen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="bg-white p-8 rounded shadow-md text-center border-t-4 border-red-500">
    <h2 class="text-red-600 text-xl font-bold mb-4">Er is iets misgegaan</h2>
    <p class="mb-6 text-gray-700"><?= htmlentities($errorMessage) ?></p>
    <a href="admin_interface.php" class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700 transition">Terug naar overzicht</a>
</div>
</body>
</html>