<?php
session_start();

require_once 'includes/database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];

            if ($user['user_type'] == 2) {
                header("Location: admin_interface.php");
            } else {
                header("Location: profile.php");
            }
            exit;


        } else {
            $error = "E-mailadres of wachtwoord is onjuist.";
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
        $error = "Er ging iets mis met inloggen. Probeer het later opnieuw.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - GRILLZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> .text-gold { color: #D4AF37; } .bg-gold { background-color: #D4AF37; } </style>
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen" style="background-image: url('https://images.unsplash.com/photo-1616091093747-4d8b9d31e9c5?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-blend-mode: overlay;">

<div class="w-full max-w-sm bg-white rounded-lg shadow-2xl overflow-hidden p-8 z-10">

    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Welkom Terug</h2>
        <p class="text-gray-500 text-sm">Log in om je reserveringen te beheren</p>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center text-sm">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Wachtwoord</label>
            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500" required>
        </div>

        <button type="submit" class="w-full bg-gray-900 text-gold font-bold py-3 rounded hover:bg-gray-800 transition duration-300">
            INLOGGEN
        </button>
    </form>

    <p class="text-center text-gray-500 text-xs mt-6">
        Nog geen account? <a href="registratie.php" class="text-yellow-600 hover:underline">Registreer hier</a>.
    </p>
</div>

</body>

</html>
