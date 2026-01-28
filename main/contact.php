<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Contact</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

        :root { --gold: #d2ad3c; --nav-bg: #0F172B; }
        .dm-sans { font-family: 'DM Sans', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
    </style>
</head>

<body class="min-h-screen bg-white antialiased text-slate-900">
<header class="bg-[color:var(--nav-bg)]">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <div class="text-white font-extrabold text-lg tracking-wide flex items-baseline gap-2">
                <span>GRILLAZ</span><span class="text-[color:var(--gold)]">.NL</span>
            </div>

            <nav aria-label="Primary" class="hidden md:flex gap-6">
                <a class="text-slate-200 hover:text-white" href="index.php#products">Producten</a>
                <a class="text-slate-200 hover:text-white" href="index.php">Home</a>
            </nav>
        </div>

        <div class="flex items-center gap-3">
            <button class="hidden md:inline-block px-4 py-2 rounded-md border-[1.05px] border-[color:var(--gold)] text-[color:var(--gold)] bg-transparent font-semibold transition-colors duration-150 hover:bg-[color:var(--gold)] hover:text-slate-900">Inloggen</button>
            <button class="hidden md:inline-block px-4 py-2 rounded-md border-[1.05px] border-[color:var(--gold)] text-[color:var(--gold)] bg-transparent font-semibold transition-colors duration-150 hover:bg-[color:var(--gold)] hover:text-slate-900">Registreren</button>
        </div>
    </div>
</header>


<main class="flex items-start justify-center pt-20 pb-24 px-4">
    <section class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h1 id="contact-title" class="text-center dm-sans font-bold tracking-wider leading-tight uppercase text-2xl md:text-3xl mb-6 text-slate-900">CONTACT</h1>

        <form action="#" method="post" novalidate class="space-y-4">
            <label for="naam" class="sr-only">Naam</label>
            <input id="naam" name="naam" type="text" placeholder="Voor- en Achternaam" class="w-full px-4 py-3 rounded-md border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />

            <label for="email" class="sr-only">Email</label>
            <input id="email" name="email" type="email" placeholder="Email" class="w-full px-4 py-3 rounded-md border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />

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

<footer class="bg-[color:var(--nav-bg)] text-slate-300">
    <div class="max-w-6xl mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-8">

            <div class="md:w-1/3">
                <div class="flex items-baseline gap-2">
                    <span class="text-white font-extrabold text-lg">GRILLAZ</span>
                    <span class="text-[color:var(--gold)] font-extrabold">.NL</span>
                </div>
                <p class="mt-4 text-sm text-slate-400 max-w-xs">
                    Jouw lach, onze passie. <br />
                    Custom made in Maassluis.
                </p>
            </div>


            <div class="md:w-1/3">
                <h3 class="text-white font-semibold mb-4">Contact</h3>
                <address class="not-italic space-y-2 text-sm text-slate-400">
                    <div>Hoofdstraat 123</div>
                    <div>1234 AB Plaats</div>
                    <div>Nederland</div>
                    <div class="mt-2">06 - 123 45 678</div>
                </address>
            </div>


            <div class="md:w-1/3">
                <h3 class="text-white font-semibold mb-4">Volg ons</h3>
                <div class="flex items-center gap-3">

                    <a href="#" aria-label="Instagram" class="p-2 rounded-full bg-slate-800 hover:bg-slate-700 transition">
                        <svg class="w-5 h-5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                            <path d="M16 11.37a4 4 0 1 1-7.999.001A4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.5" y2="6.5"></line>
                        </svg>
                    </a>


                    <a href="#" aria-label="TikTok" class="p-2 rounded-full bg-slate-800 hover:bg-slate-700 transition">
                        <svg class="w-5 h-5 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M16 3h2a4 4 0 0 0 0 8h-1v4.5A5.5 5.5 0 1 1 11.5 10V6h2v7a3.5 3.5 0 1 0 3.5 3.5V3z" />
                        </svg>
                    </a>


                    <a href="#" aria-label="Facebook" class="p-2 rounded-full bg-slate-800 hover:bg-slate-700 transition">
                        <svg class="w-5 h-5 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M22 12.07C22 6.48 17.52 2 11.93 2S1.86 6.48 1.86 12.07c0 4.98 3.66 9.11 8.44 9.9v-6.99H8.1v-2.91h2.2V9.8c0-2.17 1.3-3.37 3.29-3.37.95 0 1.95.17 1.95.17v2.15h-1.1c-1.08 0-1.42.67-1.42 1.36v1.63h2.42l-.39 2.91h-2.03v6.99c4.78-.79 8.44-4.92 8.44-9.9z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>


        <div class="border-t border-slate-700 mt-8"></div>


        <div class="mt-6 text-center text-sm text-slate-400">
            © 2025 Grillaz.nl – Alle rechten voorbehouden.
        </div>
    </div>
</footer>
</body>

</html>
