<?php

require_once 'includes/database.php';

try {
    $stmt = $conn->query("SELECT * FROM products WHERE bestseller = 1");
    $bestsellers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $bestsellers = [];
}

?>

<section id="products" class="container mx-auto px-4 py-16 relative group">
    <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Onze <span class="text-yellow-600">Bestsellers</span></h2>

    <div class="relative">

        <button id="scrollLeft" class="absolute -left-4 md:-left-14 top-1/2 transform -translate-y-1/2 z-10 bg-gray-900 text-gold p-3 rounded-full opacity-0 group-hover:opacity-100 transition shadow-lg hidden md:block hover:scale-110">
            &#10094;
        </button>

        <div id="carouselContainer" class="flex overflow-x-auto space-x-6 pb-8 snap-x snap-mandatory scrollbar-hide" style="scroll-behavior: smooth;">

            <?php if (count($bestsellers) > 0): ?>
                <?php foreach ($bestsellers as $product): ?>
                    <div class="w-[85%] md:w-[calc((100%-3rem)/3)] snap-center flex-shrink-0 pb-4">
                        <div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300 flex flex-col h-full">

                            <div class="diamond-card relative">
                                <img class="w-full h-64 object-cover"
                                     src="<?= htmlspecialchars($product['image_path'] ?? 'images/placeholder.png') ?>"
                                     alt="<?= htmlspecialchars($product['name']) ?>">

                                <div class="sparkle s1"></div>
                                <div class="sparkle s2"></div>
                                <div class="sparkle s3"></div>
                            </div>

                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="font-bold text-xl mb-2"><?= htmlspecialchars($product['name']) ?></h3>

                                <p class="text-gray-600 text-sm mb-4 flex-grow">
                                    <?= htmlspecialchars(substr($product['description'] ?? 'Premium kwaliteit custom grillz.', 0, 100)) ?>...
                                </p>

                                <div class="flex justify-between items-center mt-auto">
                                    <span class="text-xl font-bold text-gray-900">€ <?= number_format($product['price'], 2, ',', '.') ?></span>
                                    <a href="<?= isset($_SESSION['user_id']) ? 'profile.php#new-booking' : 'login.php' ?>"
                                       class="sheen-btn bg-gray-900 text-white hover:bg-gray-700 py-2 px-4 rounded transition">
                                        Reserveren
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="w-full text-center text-gray-500">Nog geen bestsellers geselecteerd in de database.</p>
            <?php endif; ?>

        </div>

        <button id="scrollRight" class="absolute -right-4 md:-right-14 top-1/2 transform -translate-y-1/2 z-10 bg-gray-900 text-gold p-3 rounded-full opacity-0 group-hover:opacity-100 transition shadow-lg hidden md:block hover:scale-110">
            &#10095;
        </button>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <script>
        const container = document.getElementById('carouselContainer');
        document.getElementById('scrollLeft').onclick = () => { container.scrollLeft -= 350; };
        document.getElementById('scrollRight').onclick = () => { container.scrollLeft += 350; };
    </script>
</section>