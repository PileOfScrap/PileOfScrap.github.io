<?php
// make sure to include adminauth.php
require_once 'includes/adminauth.php';
require_once 'includes/database.php';

// ==========================================
// PART 1: TEAMMATE'S SEARCH LOGIC (UNTOUCHED)
// ==========================================

$sql = "SELECT id, first_name, last_name, user_type, email, phone FROM customers";
$params = [];
$conditions = [];

if (!empty($_POST['input'])) {
    $conditions[] = '(first_name LIKE :search OR last_name LIKE :search)';
    $params[':search'] = '%' . $_POST['input'] . '%';
}

if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$allowedOrder = ['first_name', 'last_name'];
if (!empty($_POST['order']) && in_array($_POST['order'], $allowedOrder, true)) {
    $sql .= ' ORDER BY ' . $_POST['order'];
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================================
// PART 2: ADMIN CALENDAR LOGIC
// ==========================================

$morning_time = "10:00:00";
$afternoon_time = "14:00:00";
$week_offset = isset($_GET['week_offset']) ? (int)$_GET['week_offset'] : 0;

// Calculate dates for the view
$current_monday = strtotime("last monday", strtotime("tomorrow"));
$target_monday = strtotime("+$week_offset weeks", $current_monday);
$week_dates = [];
for ($i = 0; $i < 6; $i++) {
    $week_dates[] = date("Y-m-d", strtotime("+$i days", $target_monday));
}
$week_label = date("d M", strtotime($week_dates[0])) . " - " . date("d M", strtotime($week_dates[5]));

// Fetch ALL reservations for this week with Customer Names
$placeholders = str_repeat('?,', count($week_dates) - 1) . '?';
$sql_res = "SELECT r.*, c.first_name, c.last_name, c.email 
            FROM reservations r 
            JOIN customers c ON r.customer_id = c.id 
            WHERE r.date IN ($placeholders)";
$stmt_res = $conn->prepare($sql_res);
$stmt_res->execute($week_dates);
$all_reservations = $stmt_res->fetchAll(PDO::FETCH_ASSOC);

function getBooking($date, $time, $reservations) {
    foreach ($reservations as $res) {
        if ($res['date'] == $date && substr($res['time'], 0, 5) == substr($time, 0, 5)) {
            return $res;
        }
    }
    return null;
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // A. CREATE FOLLOW-UP
    if (isset($_POST['action']) && $_POST['action'] == 'create_admin') {
        try {
            $conn->beginTransaction();
            $check = $conn->prepare("SELECT id FROM reservations WHERE date = ? AND time = ?");
            $check->execute([$_POST['date'], $_POST['time']]);
            if($check->rowCount() > 0) throw new Exception("Tijdslot bezet!");

            $stmt = $conn->prepare("INSERT INTO reservations (customer_id, date, time, notes) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['customer_id'], $_POST['date'], $_POST['time'], $_POST['notes']]);
            $res_id = $conn->lastInsertId();

            if(!empty($_POST['products'])) {
                $p_stmt = $conn->prepare("INSERT INTO reservation_products (reservation_id, product_id) VALUES (?, ?)");
                foreach($_POST['products'] as $pid) $p_stmt->execute([$res_id, $pid]);
            }
            $conn->commit();
            header("Location: admin_interface.php?week_offset=$week_offset"); exit;
        } catch(Exception $e) { $conn->rollBack(); echo "<script>alert('Fout: ".$e->getMessage()."');</script>"; }
    }

    // B. DELETE RESERVATION
    if (isset($_POST['action']) && $_POST['action'] == 'delete_admin') {
        $del_id = $_POST['reservation_id'];
        $conn->prepare("DELETE FROM reservation_products WHERE reservation_id = ?")->execute([$del_id]);
        $conn->prepare("DELETE FROM reservations WHERE id = ?")->execute([$del_id]);
        header("Location: admin_interface.php?week_offset=$week_offset"); exit;
    }

    // C. EDIT RESERVATION
    if (isset($_POST['action']) && $_POST['action'] == 'edit_admin') {
        $conn->prepare("UPDATE reservations SET notes = ? WHERE id = ?")
                ->execute([$_POST['notes'], $_POST['reservation_id']]);
        header("Location: admin_interface.php?week_offset=$week_offset"); exit;
    }
}

$all_products = $conn->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - GRILLAZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .text-gold { color: #D4AF37; }
        .bg-gold { background-color: #D4AF37; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 50; justify-content: center; align-items: center; }
        .modal.open { display: flex; }
    </style>
</head>

<body class="bg-gray-100 font-sans pt-24 pb-10">

<header class="fixed w-full z-50 top-0 bg-gray-900 shadow-lg p-4 h-20 flex items-center">
    <div class="container mx-auto max-w-6xl flex justify-between items-center px-4">
        <h1 class="text-3xl font-bold text-gold tracking-wide">Beheer</h1>

        <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition shadow-md">
            Uitloggen
        </a>
    </div>
</header>

<div class="container mx-auto px-4 max-w-6xl">

    <div class="bg-white rounded-lg shadow-lg p-6 mb-10 border-t-4 border-gold">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Agenda Weekoverzicht</h2>

            <div class="flex items-center space-x-4">
                <a href="admin_interface.php?week_offset=<?= $week_offset - 1 ?>" class="bg-gray-200 hover:bg-gold hover:text-white px-3 py-1 rounded transition text-xl font-bold">&#10094;</a>
                <span class="font-bold text-gray-700 text-lg uppercase tracking-wide"><?= $week_label ?></span>
                <a href="admin_interface.php?week_offset=<?= $week_offset + 1 ?>" class="bg-gray-200 hover:bg-gold hover:text-white px-3 py-1 rounded transition text-xl font-bold">&#10095;</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                <tr>
                    <th class="p-3 border bg-gray-900 text-gold w-24">Tijd</th>
                    <?php foreach(['Ma','Di','Wo','Do','Vr','Za'] as $i => $d): ?>
                        <th class="p-3 border bg-gray-900 text-white w-1/6">
                            <?= $d ?> <br> <span class="text-xs font-normal text-gray-400"><?= date('d/m', strtotime($week_dates[$i])) ?></span>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="p-3 border font-bold text-center bg-gray-50 text-gray-600">10:00</td>
                    <?php foreach($week_dates as $date):
                        $booking = getBooking($date, $morning_time, $all_reservations);
                        ?>
                        <td class="p-2 border h-32 align-top transition hover:bg-gray-50">
                            <?php if($booking): ?>
                                <div onclick='openEditModal(<?= json_encode($booking) ?>)' class="cursor-pointer bg-green-100 border-l-4 border-green-500 p-2 text-xs shadow-sm hover:shadow-md transition h-full">
                                    <div class="font-bold text-green-800 text-sm mb-1"><?= htmlspecialchars($booking['first_name'].' '.$booking['last_name']) ?></div>
                                    <div class="text-green-600 truncate mb-1"><?= htmlspecialchars($booking['email']) ?></div>
                                    <?php if($booking['notes']): ?>
                                        <div class="text-gray-500 italic truncate text-[10px] bg-white bg-opacity-50 p-1 rounded">"<?= htmlspecialchars($booking['notes']) ?>"</div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="h-full flex items-center justify-center text-gray-300 text-xs uppercase tracking-widest bg-gray-50/50">Vrij</div>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>

                <tr>
                    <td class="p-3 border font-bold text-center bg-gray-50 text-gray-600">14:00</td>
                    <?php foreach($week_dates as $date):
                        $booking = getBooking($date, $afternoon_time, $all_reservations);
                        ?>
                        <td class="p-2 border h-32 align-top transition hover:bg-gray-50">
                            <?php if($booking): ?>
                                <div onclick='openEditModal(<?= json_encode($booking) ?>)' class="cursor-pointer bg-blue-100 border-l-4 border-blue-500 p-2 text-xs shadow-sm hover:shadow-md transition h-full">
                                    <div class="font-bold text-blue-800 text-sm mb-1"><?= htmlspecialchars($booking['first_name'].' '.$booking['last_name']) ?></div>
                                    <div class="text-blue-600 truncate mb-1"><?= htmlspecialchars($booking['email']) ?></div>
                                    <?php if($booking['notes']): ?>
                                        <div class="text-gray-500 italic truncate text-[10px] bg-white bg-opacity-50 p-1 rounded">"<?= htmlspecialchars($booking['notes']) ?>"</div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="h-full flex items-center justify-center text-gray-300 text-xs uppercase tracking-widest bg-gray-50/50">Vrij</div>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 border-t-4 border-gray-800">

        <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-4">Klantenoverzicht</h1>

        <form method="post" class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="input" class="block text-gray-700 font-bold mb-2">Zoek op naam</label>
                    <input type="text" name="input" id="input" value="<?= isset($_POST['input']) ? htmlentities($_POST['input']) : '' ?>" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-yellow-500 shadow-sm" placeholder="Jan">
                </div>
                <div>
                    <p class="block text-gray-700 font-bold mb-2">Sorteer op</p>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer bg-white px-3 py-2 rounded border shadow-sm"><input type="radio" name="order" value="first_name" class="mr-2"> Voornaam</label>
                        <label class="flex items-center cursor-pointer bg-white px-3 py-2 rounded border shadow-sm"><input type="radio" name="order" value="last_name" class="mr-2"> Achternaam</label>
                    </div>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-gray-900 text-gold font-bold py-2 px-6 rounded shadow hover:bg-gray-800 transition w-full md:w-auto">Toepassen</button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-900 text-gold">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Naam</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">E-mail</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Type</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Actie</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <?php foreach ($result as $user): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800"><?= htmlentities($user['id']) ?></td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= htmlentities($user['first_name'] . ' ' . $user['last_name']) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= htmlentities($user['email']) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            <?php
                            $badgeClass = match($user['user_type']) {
                                1 => 'bg-blue-100 text-blue-800',
                                2 => 'bg-purple-100 text-purple-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            $typeName = match($user['user_type']) {
                                1 => 'Medewerker',
                                2 => 'Admin',
                                default => 'Klant'
                            };
                            ?>
                            <span class="px-2 py-1 rounded text-xs font-bold <?= $badgeClass ?>"><?= $typeName ?></span>
                        <td class="px-4 py-3 text-sm flex gap-3">
                            <button onclick="openPlanModal(<?= $user['id'] ?>, '<?= htmlentities($user['first_name'].' '.$user['last_name']) ?>')"
                                    class="text-blue-600 font-bold hover:text-blue-800 hover:underline flex items-center">
                                <span class="text-lg mr-1">+</span> Inplannen
                            </button>

                            <a href="editotherprofile.php?id=<?= $user['id'] ?>" class="text-gray-500 font-semibold hover:text-gold hover:underline mt-1">Bewerken</a>

                            <a href="delete_other_profile.php?id=<?= $user['id'] ?>"
                               class="text-red-500 font-semibold hover:text-red-700 hover:underline mt-1"
                               onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                                Verwijderen
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="planModal" class="modal">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md border-t-4 border-blue-600">
        <h2 class="text-xl font-bold mb-4">Vervolgafspraak Inplannen</h2>
        <p class="mb-4 text-sm text-gray-600 bg-gray-100 p-2 rounded">Klant: <span id="planCustomerName" class="font-bold text-black text-lg ml-2"></span></p>

        <form method="POST">
            <input type="hidden" name="action" value="create_admin">
            <input type="hidden" name="customer_id" id="planCustomerId">

            <div class="mb-4">
                <label class="block text-sm font-bold mb-1">Datum</label>
                <input type="date" name="date" class="w-full border p-2 rounded focus:border-blue-500 focus:outline-none" required min="<?= date('Y-m-d') ?>">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-1">Tijd</label>
                <select name="time" class="w-full border p-2 rounded focus:border-blue-500 focus:outline-none">
                    <option value="10:00:00">10:00 (Ochtend)</option>
                    <option value="14:00:00">14:00 (Middag)</option>
                </select>
            </div>

            <div class="mb-4 h-32 overflow-y-auto border p-2 rounded bg-gray-50">
                <p class="text-xs font-bold mb-2 uppercase text-gray-500">Behandeling:</p>
                <?php foreach($all_products as $prod): ?>
                    <label class="flex items-center mb-1 text-sm hover:bg-gray-100 p-1 rounded cursor-pointer">
                        <input type="checkbox" name="products[]" value="<?=$prod['id']?>" class="mr-2 h-4 w-4 text-blue-600"> <?=$prod['name']?>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-1">Opmerking</label>
                <textarea name="notes" class="w-full border p-2 rounded focus:border-blue-500 focus:outline-none" placeholder="Bijv. controle na 2 weken..."></textarea>
            </div>

            <div class="flex justify-end gap-2 border-t pt-4">
                <button type="button" onclick="closeModal('planModal')" class="text-gray-500 hover:text-black px-4 py-2 font-bold">Annuleren</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Opslaan</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md border-t-4 border-gold">
        <h2 class="text-xl font-bold mb-2 text-gray-900">Afspraak Beheren</h2>
        <p class="text-sm text-gray-600 mb-6">Klant: <span id="editCustomerName" class="font-bold text-black"></span></p>

        <form method="POST">
            <input type="hidden" name="action" value="edit_admin">
            <input type="hidden" name="reservation_id" id="editResId">

            <div class="mb-6">
                <label class="block text-sm font-bold mb-1 text-gray-700">Opmerking Wijzigen</label>
                <textarea name="notes" id="editNotes" class="w-full border p-3 rounded focus:border-gold focus:outline-none bg-gray-50" rows="3"></textarea>
            </div>

            <div class="flex justify-between items-center pt-4 border-t">
                <button type="submit" name="action" value="delete_admin" onclick="return confirm('Weet je zeker dat je deze afspraak wilt verwijderen?')" class="text-red-500 hover:text-red-700 underline text-sm font-bold">
                    Verwijderen
                </button>

                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('editModal')" class="text-gray-500 font-bold hover:text-black">Sluiten</button>
                    <button type="submit" class="bg-gray-900 text-gold font-bold py-2 px-6 rounded hover:bg-gray-800 shadow">Opslaan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openPlanModal(id, name) {
        document.getElementById('planCustomerId').value = id;
        document.getElementById('planCustomerName').innerText = name;
        document.getElementById('planModal').classList.add('open');
    }

    function openEditModal(booking) {
        document.getElementById('editResId').value = booking.id;
        document.getElementById('editCustomerName').innerText = booking.first_name + ' ' + booking.last_name;
        document.getElementById('editNotes').value = booking.notes;
        document.getElementById('editModal').classList.add('open');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('open');
        }
    }
</script>

</body>
</html>