<?php
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRILLAZ - Custom Jewelry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="includes/styles.css">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex flex-col min-h-screen">

<?php require_once 'includes/navigation.php'; ?>

<div class="relative h-screen flex items-center justify-center
            bg-contain md:bg-contain bg-center bg-no-repeat bg-black"
     style="background-image: url('images/grills.png');">
    <div class="absolute inset-0 bg-black opacity-60"></div>

    <div class="relative z-9 text-center text-white p-4">
        <h1 class="gold-text md:text-6xl font-bold mb-4">Shine Like a Star</h1>
        <p class="text-xl mb-8 text-gray-300">De beste custom-fitted grillz van Nederland. Goud, Zilver & VVS
            Diamanten.</p>
        <a href="products.php"
           class="inline-block bg-gold text-gray-900 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-yellow-500 transition transform hover:scale-105">
            Bekijk Collectie
        </a>
    </div>
</div>

<?php require_once 'includes/bestsellers.php'; ?>

<?php require_once 'includes/footer.php'; ?>

</body>
</html>

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