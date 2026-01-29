<nav class="bg-gray-900 p-4 fixed w-full z-50 top-0 shadow-lg">
    <div class="container mx-auto flex items-center justify-between flex-wrap">

        <div class="flex items-center flex-shrink-0 text-white mr-6">
            <div class="font-bold text-2xl text-gold tracking-wider">
                <a href="index.php">GRILLAZ<span class="text-white">.NL</span></a>
            </div>
        </div>

        <div class="block lg:hidden flex items-center gap-4">

            <a href="<?= isset($_SESSION['user_id']) ? 'profile.php' : 'login.php' ?>"
               class="text-gold hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>

            <button id="nav-toggle" class="flex items-center px-3 py-2 border rounded text-gray-200 border-gray-400 hover:text-white hover:border-white">
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>Menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
                </svg>
            </button>
        </div>

        <div id="nav-content" class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden pt-6 lg:pt-0 transition-all duration-300 ease-in-out">

            <div class="text-sm lg:flex-grow">
                <a href="products.php" class="block mt-4 lg:inline-block lg:mt-0 text-gray-300 hover:text-gold mr-4 transition border-b border-gray-800 lg:border-none pb-2 lg:pb-0">
                    Producten
                </a>
                <a href="info.php" class="block mt-4 lg:inline-block lg:mt-0 text-gray-300 hover:text-gold mr-4 transition border-b border-gray-800 lg:border-none pb-2 lg:pb-0">
                    Info
                </a>
                <a href="contact.php" class="block mt-4 lg:inline-block lg:mt-0 text-gray-300 hover:text-gold mr-4 transition border-b border-gray-800 lg:border-none pb-2 lg:pb-0">
                    Contact
                </a>
            </div>

            <div class="mt-4 lg:mt-0">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="block lg:inline-block text-gray-400 text-sm mr-4 mb-2 lg:mb-0">
                        Hi, <?= htmlspecialchars($_SESSION['first_name'] ?? 'Klant') ?>
                    </span>

                    <a href="profile.php" class="block lg:inline-block text-center text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white transition mb-2 lg:mb-0">
                        Mijn Profiel
                    </a>
                    <a href="logout.php" class="block lg:inline-block text-center ml-0 lg:ml-2 text-sm px-4 py-2 leading-none bg-red-600 text-white rounded hover:bg-red-700 transition">
                        Uitloggen
                    </a>
                <?php else: ?>
                    <a href="login.php" class="block lg:inline-block text-center text-sm px-4 py-2 leading-none border rounded text-gold border-gold hover:border-transparent hover:text-gray-900 hover:bg-gold transition mb-2 lg:mb-0">
                        Inloggen
                    </a>
                    <a href="registration.php" class="block lg:inline-block text-center ml-0 lg:ml-2 text-sm px-4 py-2 leading-none bg-gold text-gray-900 rounded hover:bg-yellow-600 transition">
                        Registreren
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</nav>

<script>
    document.getElementById('nav-toggle').onclick = function(){
        document.getElementById("nav-content").classList.toggle("hidden");
    }
</script>