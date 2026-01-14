<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'database.php';
$user_id = $_SESSION['user_id'];

$t_user = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$t_user->execute([$user_id]);
$user_info = $t_user->fetch(PDO::FETCH_ASSOC);

$sql_query = "SELECT 
                reservations.id as res_id,
                reservations.date, 
                reservations.time, 
                reservations.notes, 
                products.name AS product_name, 
                products.price
            FROM reservations
            LEFT JOIN reservation_products ON reservations.id = reservation_products.reservation_id
            LEFT JOIN products ON reservation_products.product_id = products.id
            WHERE reservations.customer_id = :uid
            ORDER BY reservations.date DESC, reservations.time ASC";

$t_res = $conn->prepare($sql_query);
$t_res->execute(['uid' => $user_id]);
$reservations = $t_res->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn Profiel - GRILLZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> .text-gold { color: #D4AF37; } .bg-gold { background-color: #D4AF37; } </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

<nav class="bg-gray-900 p-4 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <div class="font-bold text-2xl text-gold tracking-wider"><a href="index.php">GRILLAZ<span class="text-white">.NL</span></a></div>
        <div>
            <a href="logout.php" class="text-gray-300 hover:text-white transition text-sm">Uitloggen</a>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 py-8">

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden mb-10">
        <div class="bg-gray-800 p-4 flex justify-between items-center">
            <h2 class="text-xl text-gold font-bold">Mijn Gegevens</h2>
            <a href="edit_profile.php" class="bg-gold text-gray-900 text-sm font-bold px-4 py-2 rounded hover:bg-yellow-500 transition">
                Wijzig Gegevens
            </a>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-500 text-xs uppercase font-bold mb-1">Naam</label>
                <p class="text-lg font-medium">
                    <?= htmlspecialchars($user_info['first_name'] . " " . $user_info['last_name']) ?>
                </p>
            </div>
            <div>
                <label class="block text-gray-500 text-xs uppercase font-bold mb-1">Email</label>
                <p class="text-lg font-medium">
                    <?= htmlspecialchars($user_info['email']) ?>
                </p>
            </div>
            <div>
                <label class="block text-gray-500 text-xs uppercase font-bold mb-1">Telefoon</label>
                <p class="text-lg font-medium">
                    <?= htmlspecialchars($user_info['phone'] ?? '-') ?>
                </p>
            </div>
            <div>
                <label class="block text-gray-500 text-xs uppercase font-bold mb-1">Klantnummer</label>
                <p class="text-gray-400">#<?= htmlspecialchars($user_info['id']) ?></p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Mijn Afspraken</h2>
            <a href="create_reservation.php" class="bg-gray-900 text-white font-bold px-4 py-2 rounded shadow hover:bg-gray-700 transition">
                + Nieuwe Afspraak
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <?php if (count($reservations) > 0): ?>
                <table class="min-w-full leading-normal">
                    <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs font-bold">
                        <th class="px-5 py-3 border-b border-gray-200 text-left">Datum & Tijd</th>
                        <th class="px-5 py-3 border-b border-gray-200 text-left">Product</th>
                        <th class="px-5 py-3 border-b border-gray-200 text-left">Opmerking</th>
                        <th class="px-5 py-3 border-b border-gray-200 text-right">Prijs</th>
                        <th class="px-5 py-3 border-b border-gray-200 text-right"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reservations as $res): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <div class="font-bold text-gray-900"><?= htmlspecialchars($res['date']) ?></div>
                                <div class="text-gray-500"><?= htmlspecialchars($res['time']) ?></div>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                    <span class="text-yellow-600 font-bold">
                                        <?= htmlspecialchars($res['product_name'] ?? 'Custom request') ?>
                                    </span>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm italic text-gray-500">
                                "<?= htmlspecialchars($res['notes']) ?>"
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm text-right font-mono">
                                €<?= htmlspecialchars($res['price'] ?? '0.00') ?>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="edit_reservation.php?id=<?= $res['res_id'] ?>" class="text-blue-600 hover:text-blue-900 underline mr-3">
                                    Wijzigen
                                </a>

                                <a href="delete_reservation.php?id=<?= $res['res_id'] ?>"
                                   class="text-red-600 hover:text-red-900 underline"
                                   onclick="return confirm('Weet je zeker dat je deze afspraak wilt annuleren?');">
                                    Annuleren
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    <p class="text-lg">Je hebt nog geen afspraken gepland.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

</body>
</html>