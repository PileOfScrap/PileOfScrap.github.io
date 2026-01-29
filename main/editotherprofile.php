<?php
require_once 'includes/adminauth.php';
require_once 'includes/database.php';

$errors = [];
$user = null;

// if isset post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // fetch userdata from post
    $id         = $_POST['id'] ?? null;
    $firstName  = trim($_POST['first_name'] ?? '');
    $lastName   = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $userType   = $_POST['user_type'] ?? null;

    // error checking
    if (!$id) {
        $errors[] = 'Ongeldige gebruiker.';
    }
    if ($firstName === '' || $lastName === '') {
        $errors[] = 'Voor en Achternaam moeten beide ingevuld zijn';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Ongeldig e-mailadres.';
    }
    if (!in_array($userType, ['0', '1', '2'], true)) {
        $errors[] = 'Ongeldig gebruikerstype.';
    }

    // If no errors
    if (empty($errors)) {
        // prepare queries
        $stmt = $conn->prepare("
            UPDATE customers
            SET first_name = :first_name,
                last_name  = :last_name,
                email      = :email,
                phone      = :phone,
                user_type  = :user_type
            WHERE id = :id
        ");
        // execute query
        $stmt->execute([
                ':first_name' => $firstName,
                ':last_name'  => $lastName,
                ':email'      => $email,
                ':phone'      => $phone,
                ':user_type'  => $userType,
                ':id'         => $id
        ]);

        // Send back
        header('Location: editotherprofile.php?id=' . $id . '&updated=1');
        exit;
    }
}

// if GET isset
if (isset($_GET['id'])) {
    // prepare query to fetch userdata
    $stmt = $conn->prepare("
        SELECT id, first_name, last_name, email, phone, user_type
        FROM customers
        WHERE id = :id
    ");
    // execute query
    $stmt->execute([':id' => $_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // double check
    if (!$user) {
        header('Location: admin_interface.php');
        exit;
    }

} else {
    header('Location: admin_interface.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Gebruiker bewerken - GRILLAZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .text-gold { color: #D4AF37; }
    </style>
</head>

<body class="bg-gray-100 font-sans">

<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-8">

        <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-4">
            Gebruiker bewerken
        </h1>

        <?php if (!empty($_GET['updated'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                Wijzigingen succesvol opgeslagen.
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach ($errors as $error): ?>
                    <p>⚠️ <?= htmlentities($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <!-- Postback protection -->
            <input type="hidden" name="id" value="<?= htmlentities($user['id']) ?>">

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Voornaam</label>
                <input
                        type="text"
                        name="first_name"
                        value="<?= htmlentities($user['first_name']) ?>"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500"
                        required
                >
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Achternaam</label>
                <input
                        type="text"
                        name="last_name"
                        value="<?= htmlentities($user['last_name']) ?>"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500"
                        required
                >
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">E-mailadres</label>
                <input
                        type="email"
                        name="email"
                        value="<?= htmlentities($user['email']) ?>"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500"
                        required
                >
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Telefoonnummer</label>
                <input
                        type="text"
                        name="phone"
                        value="<?= htmlentities($user['phone']) ?>"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500"
                >
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Gebruikerstype</label>
                <select
                        name="user_type"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500"
                >
                    <option value="0" <?= $user['user_type'] == 0 ? 'selected' : '' ?>>Klant</option>
                    <option value="1" <?= $user['user_type'] == 1 ? 'selected' : '' ?>>Medewerker</option>
                    <option value="2" <?= $user['user_type'] == 2 ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <div class="flex justify-between items-center">
                <a href="admin_interface.php" class="text-gray-500 hover:text-gray-800">
                    Terug
                </a>

                <button
                        type="submit"
                        class="bg-gray-900 text-gold font-bold py-3 px-6 rounded shadow hover:bg-gray-800 transition"
                >
                    Opslaan
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
