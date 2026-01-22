<?php
// make sure to include adminauth.php
require_once 'includes/adminauth.php';
require_once 'includes/database.php';

// set query to select *
$sql = "
    SELECT 
        id,
        first_name,
        last_name,
        user_type,
        email,
        phone
    FROM customers
";
//...except for password

$params = [];
$conditions = [];

// Search filter
if (!empty($_POST['input'])) {
    $conditions[] = '(first_name LIKE :search OR last_name LIKE :search)';
    $params[':search'] = '%' . $_POST['input'] . '%';
}

// Apply WHERE clause WHERE needed
if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

// append sorting statement
$allowedOrder = ['first_name', 'last_name'];
if (!empty($_POST['order']) && in_array($_POST['order'], $allowedOrder, true)) {
    $sql .= ' ORDER BY ' . $_POST['order'];
}

// Finalize query
// Execute query
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Klantenoverzicht - GRILLAZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .text-gold { color: #D4AF37; }
        .bg-gold { background-color: #D4AF37; }
    </style>
</head>

<body class="bg-gray-100 font-sans">

<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="bg-white rounded-lg shadow-lg p-8">

        <!-- Header -->
        <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-4">
            Klantenoverzicht
        </h1>

        <p class="text-gray-600 mb-8">
            Zoek en beheer geregistreerde klanten.
        </p>

        <!-- Search & Sort Form -->
        <form method="post" class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Search -->
                <div>
                    <label for="input" class="block text-gray-700 font-bold mb-2">
                        Zoek op voor- of achternaam
                    </label>
                    <input
                            type="text"
                            name="input"
                            id="input"
                            value="<?= isset($input) ? htmlentities($input) : '' ?>"
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500"
                            placeholder="Jan"
                    >
                </div>

                <!-- Sort -->
                <div>
                    <p class="block text-gray-700 font-bold mb-2">Sorteer op</p>

                    <label class="flex items-center mb-2 cursor-pointer">
                        <input type="radio" name="order" value="first_name" class="mr-2">
                        Voornaam
                    </label>

                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="order" value="last_name" class="mr-2">
                        Achternaam
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex items-end">
                    <button
                            type="submit"
                            class="bg-gray-900 text-gold font-bold py-2 px-6 rounded shadow hover:bg-gray-800 transition"
                    >
                        Toepassen
                    </button>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-900 text-gold">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Voornaam</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Achternaam</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">E-mail</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Telefoon</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Type</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Actie</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">
                            <?= htmlentities($user['id']) ?>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            <?= htmlentities($user['first_name']) ?>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            <?= htmlentities($user['last_name']) ?>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            <?= htmlentities($user['email']) ?>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            <?= htmlentities($user['phone']) ?>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            <?php
                            switch ($user['user_type']) {
                                case 0: echo 'Klant'; break;
                                case 1: echo 'Medewerker'; break;
                                case 2: echo 'Admin'; break;
                            }
                            ?>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a
                                    href="editotherprofile.php?id=<?= $user['id'] ?>"
                                    class="text-yellow-600 font-semibold hover:underline"
                            >
                                Bewerken
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</

