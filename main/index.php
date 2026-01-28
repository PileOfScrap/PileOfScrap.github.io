<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRILLAZ - Custom Jewelry</title>
    <script src="https://cdn.tailwindcss.com"></script>

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

</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex flex-col min-h-screen">

<?php require_once 'includes/navigation.php'; ?>

<div class="relative h-screen flex items-center justify-center bg-cover bg-center"
     style="background-image: url('images/grills.png');">
    <div class="absolute inset-0 bg-black opacity-60"></div>

    <div class="relative z-9 text-center text-white p-4">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">Shine Like a Star</h1>
        <p class="text-xl mb-8 text-gray-300">De beste custom-fitted grillz van Nederland. Goud, Zilver & VVS
            Diamanten.</p>
        <a href="#producten"
           class="bg-gold text-gray-900 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-yellow-500 transition transform hover:scale-105">
            Bekijk Collectie
        </a>
    </div>
</div>

<section id="products" class="container mx-auto px-4 py-16">
    <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Onze <span class="text-yellow-600">Bestsellers</span>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
            <img class="w-full h-64 object-cover" src="images/silver.png"
                 alt="Silver Grillz">
            <div class="p-6">
                <h3 class="font-bold text-xl mb-2">Silver</h3>
                <p class="text-gray-600 text-sm mb-4">Premium kwaliteit volledig zilver.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">€ 250,00</span>
                    <a href="login.php" class="text-white bg-gray-900 hover:bg-gray-700 py-2 px-4 rounded transition">
                        Reserveren
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
            <img class="w-full h-64 object-cover" src="images/gold.png"
                 alt="Gold Grillz">
            <div class="p-6">
                <h3 class="font-bold text-xl mb-2">Classic Gold (18K)</h3>
                <p class="text-gray-600 text-sm mb-4">De tijdloze klassieker. Beschikbaar voor boven- en onderkaak.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">€ 350,00</span>
                    <a href="login.php" class="text-white bg-gray-900 hover:bg-gray-700 py-2 px-4 rounded transition">
                        Reserveren
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
            <img class="w-full h-64 object-cover" src="images/diamond.png"
                 alt="Diamond Grillz">
            <div class="p-6">
                <h3 class="font-bold text-xl mb-2">VVS Diamond Set</h3>
                <p class="text-gray-600 text-sm mb-4">Voor de echte bazen. 100% echte diamanten in witgoud.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">€ 1.200,00</span>
                    <a href="login.php" class="text-white bg-gray-900 hover:bg-gray-700 py-2 px-4 rounded transition">
                        Reserveren
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
            <img class="w-full h-64 object-cover" src="images/placeholder.png" alt="Gold Grillz">
            <div class="p-6">
                <h3 class="font-bold text-xl mb-2">Classic Gold (18K)</h3>
                <p class="text-gray-600 text-sm mb-4">De tijdloze klassieker. Beschikbaar voor boven- en onderkaak.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">€ 250,00</span>
                    <a href="login.php" class="text-white bg-gray-900 hover:bg-gray-700 py-2 px-4 rounded transition">
                        Reserveren
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
            <img class="w-full h-64 object-cover" src="images/placeholder.png" alt="Silver Grillz">
            <div class="p-6">
                <h3 class="font-bold text-xl mb-2">Iced Out Silver</h3>
                <p class="text-gray-600 text-sm mb-4">Volledig zilver met handgezette zirkonia stenen.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">€ 350,00</span>
                    <a href="login.php" class="text-white bg-gray-900 hover:bg-gray-700 py-2 px-4 rounded transition">
                        Reserveren
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
            <img class="w-full h-64 object-cover" src="images/placeholder.png" alt="Diamond Grillz">
            <div class="p-6">
                <h3 class="font-bold text-xl mb-2">VVS Diamond Set</h3>
                <p class="text-gray-600 text-sm mb-4">Voor de echte bazen. 100% echte diamanten in witgoud.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">€ 1.200,00</span>
                    <a href="login.php" class="text-white bg-gray-900 hover:bg-gray-700 py-2 px-4 rounded transition">
                        Reserveren
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

</body>
</html>