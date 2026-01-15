<?php
require_once 'includes/database.php';

$success_msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    echo('test,');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $errors[] = "Vul alle verplichte velden in.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ongeldig e-mailadres.";
    }

    if (empty($errors)) {
        echo('error,');

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            echo('testest,');

            $t = $conn->prepare("INSERT INTO customers (first_name, last_name, email, phone, password, user_type) VALUES (?, ?, ?, ?, ?, ?)");

            $t->execute([$first_name, $last_name, $email, $phone, $hashed_password, 0]);

            $success_msg = "Account aangemaakt! Je kunt nu <a href='login.php' class='underline font-bold'>inloggen</a>.";

        } catch (PDOException $e) {
            echo($e->getMessage());

            if ($e->getCode() == 23000) {
                $errors[] = "Dit e-mailadres is al in gebruik.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - GRILLZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> .text-gold {
            color: #D4AF37;
        }

        .bg-gold {
            background-color: #D4AF37;
        } </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="w-full max-w-md bg-white rounded-lg shadow-xl overflow-hidden p-8">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">Maak je account</h2>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php foreach ($errors as $error) {
                echo "<p>⚠️ $error</p>";
            } ?>
        </div>
    <?php endif; ?>

    <?php if ($success_msg): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $success_msg ?>
        </div>
    <?php endif; ?>

    <form action="registration.php" method="POST">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Voornaam</label>
                <input type="text" name="first_name"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-yellow-500" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Achternaam</label>
                <input type="text" name="last_name"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-yellow-500" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email"
                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-yellow-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Telefoonnummer (Optioneel)</label>
            <input type="text" name="phone"
                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-yellow-500"
                   placeholder="0612345678">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Wachtwoord</label>
            <input type="password" name="password"
                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-yellow-500" required>
        </div>

        <button type="submit"
                class="w-full bg-gray-900 text-gold font-bold py-3 rounded hover:bg-gray-800 transition duration-300">
            REGISTREREN
        </button>
    </form>

    <p class="text-center text-gray-500 text-xs mt-6">
        Al een account? <a href="login.php" class="text-yellow-600 hover:underline">Log hier in</a>.
    </p>
</div>

</body>

</html>
