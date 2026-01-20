<?php
/*
    ------ Stap 1: authenticatie
    Start de sessie
    IF gebruiker NIET is ingelogd:
        Terug sturen naar login.php
        STOP script
    Create verbinding met database
    variabelen (errors array, user_id) initialiseren

    ------Stap 2: verwerken van invoer (POST/POSTBACK)
    IF de aanvraag-methode POST is:
        haal invoer op (voornaam, achternaam, email, telefoon) en maak schoon (trim)

        Validatie
        IF verplichte velden leeg zijn:
            "Vul alle velden in"
        IF email ongeldig is:
            "Ongeldig emailadres"

        Database Update
        IF geen errors:
            Probeer (Try):
                maak SQL UPDATE query (prepared statement)
                voer query uit met parameters
                update de sessie-variabele (zodat naam wijzigt)
                geef een succesmelding
                refresh pagina na 1 seconde
            vang (Catch) database fout:
                IF email 'dubbele invoer' is -> Meld "Email bestaat al"
                ELSE -> Log fout en meld "Algemene fout"

    ------Stap 3: Gegevens ophalen (GET Situatie & Formulier vullen)
    Dit gebeurt altijd, zodat het formulier gevuld is met de (nieuwe) data
    maak SQL SELECT query (Haal klant op via ID)
    voer query uit
    sla resultaat op in variabele $user

    -----Stap 4: weergeven
    HTML weergeven:
        IF er errors zijn -> toon error lijst
        IF er succes is -> geef succesmelding
        laat formulier zien:
            - VUL velden met data uit $user (Sticky form)
            - Actie = edit_profile.php (Postback)
*/

require_once 'includes/database.php';
require_once 'includes/adminauth.php';
$user_id = $_SESSION['user_id'];

$errors = [];
$success_msg = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
        $errors[] = "Vul alle verplichte velden in.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ongeldig e-mailadres.";
    }

    if (empty($errors)) {
        try {
            $sql = "UPDATE customers 
                    SET first_name = ?, last_name = ?, email = ?, phone = ? 
                    WHERE id = ?";

            $t = $conn->prepare($sql);
            $t->execute([$first_name, $last_name, $email, $phone, $user_id]);

            $_SESSION['first_name'] = $first_name;

            $success_msg = "Gegevens succesvol aangepast!";

            header("refresh:1;url=profile.php");

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Dit e-mailadres is al in gebruik.";
            } else {
                error_log($e->getMessage());
                $errors[] = "Er ging iets mis met opslaan.";
            }
        }
    }
}

$t = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$t->execute([$user_id]);
$user = $t->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wijzig Profiel - GRILLAZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> .text-gold { color: #D4AF37; } .bg-gold { background-color: #D4AF37; } </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-8">

<div class="w-full max-w-lg bg-white rounded-lg shadow-xl overflow-hidden p-8">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Gegevens Wijzigen</h2>
        <a href="profile.php" class="text-sm text-gray-500 hover:text-gray-800">Terug naar profiel</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php foreach($errors as $error) { echo "<p>⚠️ $error</p>"; } ?>
        </div>
    <?php endif; ?>

    <?php if ($success_msg): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $success_msg ?>
        </div>
    <?php endif; ?>

    <form action="edit_profile.php" method="POST">

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Voornaam</label>
                <input type="text" name="first_name"
                       value="<?= htmlspecialchars($user['first_name']) ?>"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-yellow-500" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Achternaam</label>
                <input type="text" name="last_name"
                       value="<?= htmlspecialchars($user['last_name']) ?>"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-yellow-500" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($user['email']) ?>"
                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-yellow-500" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Telefoonnummer</label>
            <input type="text" name="phone"
                   value="<?= htmlspecialchars($user['phone']) ?>"
                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-yellow-500">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="w-full bg-gray-900 text-gold font-bold py-3 rounded hover:bg-gray-800 transition duration-300">
                OPSLAAN
            </button>
        </div>

    </form>
</div>

</body>

</html>

