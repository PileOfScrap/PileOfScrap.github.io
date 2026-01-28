<?php

session_start();

require_once 'includes/database.php';

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infopage - GRILLZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> .text-gold { color: #D4AF37; } .bg-gold { background-color: #D4AF37; } </style>
</head>
<body>

<?php require_once 'includes/navigation.php';?>

<section class="relative bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-28 grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">

        <!-- Tekst -->
        <div>

            <h1 class="text-4xl lg:text-5xl font-semibold text-gray-900 leading-tight mb-6">
                Alles wat je moet weten<br>
                over Grillaz
            </h1>

            <p class="text-lg text-gray-600 mb-8 max-w-xl">
                Onze grillz worden met zorg en precisie gemaakt, zodat jij ze
                veilig en comfortabel kunt dragen. Met de juiste verzorging
                blijven ze jarenlang mooi.
            </p>

            <div class="flex flex-wrap gap-4">
                <a
                        href="#veiligheid"
                        class="inline-block bg-yellow-500 text-white px-8 py-3 rounded-xl font-medium hover:bg-yellow-600 transition"
                >
                    Verzorging van je grillz
                </a>

                <a
                        href="#materialen"
                        class="inline-block border border-gray-300 px-8 py-3 rounded-xl font-medium text-gray-700 hover:border-gray-400 transition"
                >
                    Onze materialen
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Over GRILLAZ -->
<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <!-- Tekst -->
        <div>
            <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                Over GRILLAZ.NL
            </h2>

            <p class="mb-4 leading-relaxed">
                GRILLAZ.NL is dé specialist in custom-made grillz in Nederland.
                Gevestigd in Maassluis, combineren we vakmanschap met moderne
                technieken om de perfecte grillz voor jou te creëren.
            </p>

            <p class="mb-4 leading-relaxed">
                Onze passie is jouw tanden laten schitteren als nooit tevoren.
                Elk paar grillz wordt met zorg en precisie op maat gemaakt,
                speciaal voor jouw gebit. Van klassiek goud tot VVS diamanten –
                wij maken jouw droomgrillz werkelijkheid.
            </p>

            <p class="leading-relaxed">
                Met jarenlange ervaring en een scherp oog voor detail, leveren we
                alleen de hoogste kwaliteit. Jouw tevredenheid en een perfecte
                pasvorm staan bij ons centraal.
            </p>
        </div>

        <!-- Afbeelding -->
        <div class="w-full">
            <img
                    src="images/baddie.jpg"
                    alt="Grillz"
                    class="rounded-xl shadow-lg max-w-md mx-auto object-cover"
            />
        </div>

    </div>
</section>

<!-- Onze Materialen -->
<section class="bg-gray-50 py-20" id="materialen">
    <div class="max-w-7xl mx-auto px-6">

        <h2 class="text-3xl font-semibold text-center text-gray-900 mb-14">
            Onze Materialen
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            <!-- Goud -->
            <div class="bg-white rounded-2xl shadow-md p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-yellow-400 flex items-center justify-center text-white text-xl font-semibold">
                    Au
                </div>
                <h3 class="text-xl font-semibold mb-3">Goud</h3>
                <p class="text-gray-600 leading-relaxed">
                    Beschikbaar in 10K, 14K en 18K goud. Kies uit geelgoud,
                    witgoud of roségoud voor een luxueuze uitstraling die
                    nooit uit de mode raakt.
                </p>
            </div>

            <!-- Zilver -->
            <div class="bg-white rounded-2xl shadow-md p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-gray-400 flex items-center justify-center text-white text-xl font-semibold">
                    Ag
                </div>
                <h3 class="text-xl font-semibold mb-3">Zilver</h3>
                <p class="text-gray-600 leading-relaxed">
                    925 sterling zilver van topkwaliteit. Perfect voor een
                    strakke, moderne look tegen een betaalbare prijs zonder
                    concessies aan kwaliteit.
                </p>
            </div>

            <!-- VVS Diamanten -->
            <div class="bg-white rounded-2xl shadow-md p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-yellow-400 flex items-center justify-center text-white text-xl">
                    💎
                </div>
                <h3 class="text-xl font-semibold mb-3">VVS Diamanten</h3>
                <p class="text-gray-600 leading-relaxed">
                    Hoogwaardige VVS diamanten met perfecte helderheid.
                    Hand-selected en professioneel gezet voor maximale
                    schittering en duurzaamheid.
                </p>
            </div>

        </div>
    </div>
</section>
<section class="max-w-7xl mx-auto px-6 py-20" id="veiligheid">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">

        <!-- Afbeelding -->
        <div>
            <img
                    src="images/baddieaanhetwerk.jpg"
                    alt="Grillz"
                    class="rounded-xl shadow-lg max-w-md mx-auto object-cover"
            />
        </div>

        <!-- Tekst -->
        <div>
            <h2 class="text-3xl font-semibold text-gray-900 mb-8">
                Veiligheid &amp; Verzorging
            </h2>

            <!-- Veilig gebruik -->
            <h3 class="text-xl font-semibold text-yellow-500 mb-4">
                Veilig Gebruik
            </h3>
            <ul class="list-disc list-inside space-y-3 text-gray-700 mb-8">
                <li>Grillz zijn veilig voor dagelijks gebruik en schaden je tanden niet</li>
                <li>Ze zijn gemakkelijk in en uit te nemen wanneer je maar wilt</li>
                <li>Wij gebruiken alleen hypoallergene materialen</li>
            </ul>

            <!-- Onderhoudstips -->
            <h3 class="text-xl font-semibold text-yellow-500 mb-4">
                Onderhoudstips
            </h3>
            <ul class="list-disc list-inside space-y-3 text-gray-700">
                <li>Reinig je grillz dagelijks met lauw water en milde zeep</li>
                <li>Bewaar ze in de meegeleverde sieradendoos wanneer niet in gebruik</li>
                <li>Verwijder je grillz tijdens het eten en sporten</li>
                <li>Blijf je tanden en tandvlees goed verzorgen zoals altijd</li>
            </ul>
        </div>

    </div>
</section>
<div class="bg-[#0a1120] text-white py-16 px-4 font-sans text-center">
    <h2 class="text-4xl md:text-5xl font-semibold mb-16">Hoe Het Werkt</h2>

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

        <div class="flex flex-col items-center">
            <div class="w-20 h-20 bg-[#d4af37] rounded-full flex items-center justify-center text-3xl text-[#0a1120] font-bold mb-6">
                1
            </div>
            <h3 class="text-[#d4af37] text-xl font-bold mb-4 uppercase tracking-wide">Kies Je Ontwerp</h3>
            <p class="text-gray-300 leading-relaxed">
                Browse door onze collectie of kom met je eigen unieke design. Kies je materiaal, kleur en eventuele diamanten.
            </p>
        </div>

        <div class="flex flex-col items-center">
            <div class="w-20 h-20 bg-[#d4af37] rounded-full flex items-center justify-center text-3xl text-[#0a1120] font-bold mb-6">
                2
            </div>
            <h3 class="text-[#d4af37] text-xl font-bold mb-4 uppercase tracking-wide">Maak Een Afspraak</h3>
            <p class="text-gray-300 leading-relaxed">
                Plan een bezoek aan onze studio in Maassluis. Wij nemen een professionele afdruk van je gebit voor de perfecte pasvorm.
            </p>
        </div>

        <div class="flex flex-col items-center">
            <div class="w-20 h-20 bg-[#d4af37] rounded-full flex items-center justify-center text-3xl text-[#0a1120] font-bold mb-6">
                3
            </div>
            <h3 class="text-[#d4af37] text-xl font-bold mb-4 uppercase tracking-wide">Productie</h3>
            <p class="text-gray-300 leading-relaxed">
                Onze vakmannen gaan aan de slag met je custom grillz. Dit proces duurt ongeveer 2-3 weken, afhankelijk van het ontwerp.
            </p>
        </div>

        <div class="flex flex-col items-center">
            <div class="w-20 h-20 bg-[#d4af37] rounded-full flex items-center justify-center text-3xl text-[#0a1120] font-bold mb-6">
                4
            </div>
            <h3 class="text-[#d4af37] text-xl font-bold mb-4 uppercase tracking-wide">Ophalen & Shinen</h3>
            <p class="text-gray-300 leading-relaxed">
                Je grillz zijn klaar! Kom ze ophalen, we checken de pasvorm en je kunt direct beginnen met shinen!
            </p>
        </div>

    </div>

    <button class="bg-[#d4af37] hover:bg-[#b8962e] text-[#0a1120] font-bold py-4 px-10 rounded-full transition duration-300 text-lg">
        Start Je Bestelling
    </button>
</div>


    </div>
</div>
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
