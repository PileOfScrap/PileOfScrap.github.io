<?php
session_start();
require_once "includes/database.php";

if (isset( $_SESSION['user_id'])) {
    $userid = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT email FROM customers WHERE id = :id");
    $stmt->execute([':id' => $userid]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $result['email'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Contact</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    gold: '#D4AF37',
                    'gold-dark': '#B5952F',
                }
            }
        }
    }
</script>

<body class="min-h-screen bg-white antialiased text-slate-900">

<?php require_once 'includes/navigation.php'; ?>

<main class="flex items-start justify-center pt-20 pb-24 px-4">
    <section class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h1 id="contact-title" class="text-center dm-sans font-bold tracking-wider leading-tight uppercase text-2xl md:text-3xl mb-6 text-slate-900">CONTACT</h1>

        <form action="#" method="post" novalidate class="space-y-4">
            <label for="naam" class="sr-only">Naam</label>
            <input id="naam" name="naam" type="text" placeholder="Voor- en Achternaam" class="w-full px-4 py-3 rounded-md border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />

            <label for="email" class="sr-only">Email</label>
            <input  value=<?php echo $email?> id="email" name="email" type="email" placeholder="Email" class="w-full px-4 py-3 rounded-md border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />

            <label for="telefoon" class="sr-only">Telefoon</label>
            <input id="telefoon" name="telefoon" type="tel" placeholder="Telefoon" class="w-full px-4 py-3 rounded-md border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />

            <label for="onderwerp" class="sr-only">Onderwerp</label>
            <input id="onderwerp" name="onderwerp" type="text" placeholder="Onderwerp" class="w-full px-4 py-3 rounded-md border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />

            <label for="bericht" class="sr-only">Bericht</label>
            <textarea id="bericht" name="bericht" placeholder="Bericht" rows="5" class="w-full px-4 py-3 rounded-md border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)] resize-y"></textarea>

            <button type="submit" class="w-full mt-1 py-3 rounded-md bg-[color:var(--gold)] text-slate-900 font-bold shadow-sm">Verzend</button>
        </form>
    </section>
</main>

<?php require_once 'includes/footer.php'?>

</body>

</html>