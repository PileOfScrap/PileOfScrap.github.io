<?php
require_once 'includes/database.php';
require_once 'includes/auth.php';
require_once 'includes/colors.js';

// --- CONFIGURATION ---
$morning_time = "10:00:00"; // Top Row
$afternoon_time = "14:00:00"; // Bottom Row

// 1. Calculate the current week (Monday to Saturday)
$monday_timestamp = strtotime("last monday", strtotime("tomorrow")); // Get this week's Monday
$dates = [];
for ($i = 0; $i < 6; $i++) {
    $dates[] = date("Y-m-d", strtotime("+$i days", $monday_timestamp));
}

// 2. Fetch existing reservations for these dates
$placeholders = str_repeat('?,', count($dates) - 1) . '?';
$sql = "SELECT date, time FROM reservations WHERE date IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->execute($dates);
$booked_slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper function to check availability
function isBooked($date, $time, $booked_slots) {
    foreach ($booked_slots as $slot) {
        if ($slot['date'] == $date && substr($slot['time'], 0, 5) == substr($time, 0, 5)) {
            return true;
        }
    }
    return false;
}

// 3. Handle Form Submission
$errors = [];
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $notes = trim($_POST['notes']);
    $selected_products = $_POST['products'] ?? [];

    if (empty($date) || empty($time)) {
        $errors[] = "Selecteer een gouden tand (tijdslot) om te reserveren.";
    }
    if (empty($selected_products)) {
        $errors[] = "Kies minimaal één product/behandeling.";
    }

    // Double check database to prevent double booking race condition
    if (isBooked($date, $time, $booked_slots)) {
        $errors[] = "Helaas! Iemand anders was je net voor. Dit slot is bezet.";
    }

    if (empty($errors)) {
        try {
            $conn->beginTransaction();
            $stmt = $conn->prepare("INSERT INTO reservations (customer_id, date, time, notes) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $date, $time, $notes]);
            $reservation_id = $conn->lastInsertId();

            $stmt_product = $conn->prepare("INSERT INTO reservation_products (reservation_id, product_id) VALUES (?, ?)");
            foreach ($selected_products as $product_id) {
                $stmt_product->execute([$reservation_id, $product_id]);
            }
            $conn->commit();
            header("Location: profile.php");
            exit;
        } catch (Exception $e) {
            $conn->rollBack();
            error_log($e->getMessage());
            $errors[] = "Er ging iets mis met opslaan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Nieuwe Afspraak - GRILLAZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* --- GRILLS CSS --- */
        .grill-wrapper { display: flex; flex-direction: column; align-items: center; background: #222; padding: 20px; border-radius: 15px; margin-bottom: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.5); }
        .day-labels { display: grid; grid-template-columns: repeat(6, 40px); gap: 6px; margin-bottom: 8px; text-align: center; color: #888; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; }
        .mouth { background-color: #4a1c1c; padding: 12px 16px; border-radius: 30px; border: 3px solid #330000; display: flex; flex-direction: column; gap: 4px; }
        .jaw { display: flex; gap: 6px; }

        /* TOOTH BASE */
        .tooth { width: 40px; height: 50px; border: none; background: linear-gradient(to bottom, #fdfdfd, #e0e0e0); cursor: pointer; position: relative; transition: all 0.2s; box-shadow: inset 0 0 3px rgba(0,0,0,0.2); }
        .tooth.selected { background: linear-gradient(135deg, #b4e0ff, #ffffff); border: 2px solid #00aaff; transform: scale(1.1); z-index: 10; }

        /* SHAPES */
        .upper .incisor { border-radius: 3px 3px 12px 12px; }
        .upper .canine { border-radius: 4px 4px 50% 50%; height: 55px; }
        .lower .incisor { border-radius: 12px 12px 3px 3px; }
        .lower .canine { border-radius: 50% 50% 4px 4px; height: 55px; align-self: flex-end; }

        /* HOVER (SILVER) */
        .tooth:hover:not(.booked):not([disabled]) {
            transform: scale(1.1); z-index: 5;
            background: linear-gradient(135deg, #999 0%, #fff 50%, #777 100%);
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
        }

        /* BOOKED (GOLD) */
        .tooth.booked { cursor: not-allowed; border: 1px solid #b8860b; background: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728); opacity: 0.9; }
        .tooth.past { opacity: 0.3; cursor: not-allowed; background: #555; }

        .legend { display: flex; gap: 15px; margin-top: 10px; font-size: 0.8rem; color: #ccc; }
        .dot { width: 10px; height: 10px; display: inline-block; border-radius: 50%; margin-right: 5px; }
    </style>
</head>
<body class="bg-gray-100 font-sans pb-10">

<?php require_once 'includes/navigation.php'; ?>

<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-4">Kies je Grill</h1>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach($errors as $error) { echo "<p>⚠️ $error</p>"; } ?>
            </div>
        <?php endif; ?>

        <form action="create_reservation.php" method="POST" id="bookingForm">
            <input type="hidden" name="date" id="selected_date">
            <input type="hidden" name="time" id="selected_time">

            <div class="grill-wrapper">
                <div class="day-labels">
                    <?php
                    $days = ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'];
                    foreach($days as $day) echo "<span>$day</span>";
                    ?>
                </div>

                <div class="mouth">
                    <div class="jaw upper">
                        <?php foreach($dates as $index => $date):
                            $is_canine = ($index == 0 || $index == 5);
                            $class = $is_canine ? 'canine' : 'incisor';
                            $booked = isBooked($date, $morning_time, $booked_slots) ? 'booked' : '';
                            $past = ($date < date('Y-m-d')) ? 'past' : '';
                            ?>
                            <button type="button"
                                    class="tooth <?= $class ?> <?= $booked ?> <?= $past ?>"
                                    <?= ($booked || $past) ? 'disabled' : '' ?>
                                    onclick="selectSlot(this, '<?= $date ?>', '<?= $morning_time ?>')">
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="jaw lower">
                        <?php foreach($dates as $index => $date):
                            $is_canine = ($index == 0 || $index == 5);
                            $class = $is_canine ? 'canine' : 'incisor';
                            $booked = isBooked($date, $afternoon_time, $booked_slots) ? 'booked' : '';
                            $past = ($date < date('Y-m-d')) ? 'past' : '';
                            ?>
                            <button type="button"
                                    class="tooth <?= $class ?> <?= $booked ?> <?= $past ?>"
                                    <?= ($booked || $past) ? 'disabled' : '' ?>
                                    onclick="selectSlot(this, '<?= $date ?>', '<?= $afternoon_time ?>')">
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="legend">
                    <div><span class="dot" style="background:white"></span>Beschikbaar</div>
                    <div><span class="dot" style="background:#D4AF37"></span>Bezet (Goud)</div>
                    <div><span class="dot" style="background:#00aaff"></span>Geselecteerd</div>
                </div>

                <p id="selection-display" class="text-yellow-500 font-bold mt-4 h-6 text-sm"></p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Kies je behandeling(en)</label>
                <div class="bg-gray-50 p-4 rounded border h-48 overflow-y-scroll">
                    <?php if(count($products) > 0): ?>
                        <?php foreach($products as $prod): ?>
                            <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-200 p-2 rounded">
                                <input type="checkbox" name="products[]" value="<?= $prod['id'] ?>" class="form-checkbox h-5 w-5 text-yellow-600">
                                <span class="ml-3 text-gray-800 font-medium"><?= htmlspecialchars($prod['name']) ?></span>
                                <span class="ml-auto text-gray-500">€ <?= $prod['price'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-red-500">Geen producten gevonden.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Opmerkingen</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500"></textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="profile.php" class="text-gray-500 hover:text-gray-800">Annuleren</a>
                <button type="submit" class="bg-gray-900 text-white font-bold py-3 px-6 rounded shadow hover:bg-gray-800 transition">
                    Bevestigen
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function selectSlot(btn, date, time) {
        // Remove 'selected' from all teeth
        document.querySelectorAll('.tooth').forEach(t => t.classList.remove('selected'));

        // Add to clicked one
        btn.classList.add('selected');

        // Update hidden inputs
        document.getElementById('selected_date').value = date;
        document.getElementById('selected_time').value = time;

        // Visual feedback
        const formattedTime = time.substring(0, 5);
        document.getElementById('selection-display').innerText = `Geselecteerd: ${date} om ${formattedTime}`;
    }
</script>

</body>
</html>