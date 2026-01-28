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

<nav class="bg-gray-900 p-4 fixed w-full z-10 top-0 shadow-lg">
    <div class="container mx-auto flex items-center justify-between flex-wrap">
        <div class="flex items-center flex-shrink-0 text-white mr-6">
            <div class="font-bold text-2xl text-gold tracking-wider"><a href="index.php">GRILLAZ<span
                            class="text-white">.NL</span></a></div>
        </div>

        <div class="hidden w-full block flex-grow lg:flex lg:items-center lg:w-auto">
            <div class="text-sm lg:flex-grow">
                <a href="#products"
                   class="block mt-4 lg:inline-block lg:mt-0 text-gray-300 hover:text-gold mr-4 transition">
                    Producten
                </a>
                <a href="#contact"
                   class="block mt-4 lg:inline-block lg:mt-0 text-gray-300 hover:text-gold mr-4 transition">
                    Contact
                </a>
                <a href="info.php"
                   class="block mt-4 lg:inline-block lg:mt-0 text-gray-300 hover:text-gold mr-4 transition">
                    Over ons
                </a>
            </div>
            <div>
                <a href="login.php"
                   class="inline-block text-sm px-4 py-2 leading-none border rounded text-gold border-gold hover:border-transparent hover:text-gray-900 hover:bg-gold transition mt-4 lg:mt-0">
                    Inloggen
                </a>
                <a href="registration.php"
                   class="ml-2 inline-block text-sm px-4 py-2 leading-none bg-gold text-gray-900 rounded hover:bg-yellow-600 transition mt-4 lg:mt-0">
                    Registreren
                </a>
            </div>
        </div>
    </div>
</nav>

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

<footer id="contact" class="bg-gray-900 text-white mt-auto">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap justify-between">
            <div class="w-full md:w-1/3 mb-6 md:mb-0">
                <h3 class="font-bold text-xl text-gold mb-2">GRILLAZ.NL</h3>
                <p class="text-gray-400 text-sm">Jouw lach, onze passie. <br>Custom made in Maassluis.</p>
            </div>

            <div class="w-full md:w-1/3 mb-6 md:mb-0">
                <h4 class="font-bold mb-2">Contact</h4>
                <p class="text-gray-400 text-sm">
                    Hoofdstraat 123<br>
                    1234 AB Plaats<br>
                    info@grillaz.nl<br>
                    06 - 123 45 678
                </p>
            </div>

            <div class="w-full md:w-1/3">
                <h4 class="font-bold mb-2">Volg ons</h4>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-gold transition">Instagram</a>
                    <a href="#" class="text-gray-400 hover:text-gold transition">TikTok</a>
                    <a href="#" class="text-gray-400 hover:text-gold transition">Facebook</a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-4 text-center text-gray-500 text-sm">
            &copy; 2025 Grillaz.nl - Alle rechten voorbehouden.
        </div>
    </div>
</footer>

</body>
</html>