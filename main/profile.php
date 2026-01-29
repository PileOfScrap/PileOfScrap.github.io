<?php
require 'includes/database.php';
require_once 'includes/auth.php';

$user_id = $_SESSION['user_id'];
$errors = [];
$success_msg = "";

$morning_time = "10:00:00";
$afternoon_time = "14:00:00";

$week_offset = isset($_GET['week_offset']) ? (int)$_GET['week_offset'] : 0;

$current_monday = strtotime("last monday", strtotime("tomorrow"));
$target_monday = strtotime("+$week_offset weeks", $current_monday);

$dates = [];
for ($i = 0; $i < 6; $i++) {
    $dates[] = date("Y-m-d", strtotime("+$i days", $target_monday));
}

$week_label = date("d M", strtotime($dates[0])) . " - " . date("d M", strtotime($dates[5]));

function getWeekUrl($offset, $anchor = 'new-booking') {
    $url = "profile.php?week_offset=$offset";
    if (isset($_GET['edit_id'])) {
        $url .= "&edit_id=" . $_GET['edit_id'];
        $anchor = 'edit-booking';
    }
    return $url . "#" . $anchor;
}

function getBookedSlots($conn, $dates, $exclude_res_id = null) {
    $placeholders = str_repeat('?,', count($dates) - 1) . '?';
    $sql = "SELECT date, time FROM reservations WHERE date IN ($placeholders)";
    $params = $dates;

    if ($exclude_res_id) {
        $sql .= " AND id != ?";
        $params[] = $exclude_res_id;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function isBooked($date, $time, $booked_slots) {
    foreach ($booked_slots as $slot) {
        if ($slot['date'] == $date && substr($slot['time'], 0, 5) == substr($time, 0, 5)) {
            return true;
        }
    }
    return false;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $notes = trim($_POST['notes']);
    $selected_products = $_POST['products'] ?? [];
    $action_type = $_POST['action_type'] ?? '';

    if (empty($date) || empty($time)) $errors[] = "Selecteer een tijdslot.";
    if (empty($selected_products)) $errors[] = "Kies minimaal één behandeling.";

    if ($action_type === 'create' && empty($errors)) {
        $booked_slots = getBookedSlots($conn, $dates);
        if (isBooked($date, $time, $booked_slots)) {
            $errors[] = "Dit slot is helaas al bezet.";
        } else {
            try {
                $conn->beginTransaction();
                $stmt = $conn->prepare("INSERT INTO reservations (customer_id, date, time, notes) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $date, $time, $notes]);
                $res_id = $conn->lastInsertId();

                $stmt_prod = $conn->prepare("INSERT INTO reservation_products (reservation_id, product_id) VALUES (?, ?)");
                foreach ($selected_products as $pid) $stmt_prod->execute([$res_id, $pid]);

                $conn->commit();
                header("Location: profile.php");
                exit;
            } catch (Exception $e) { $conn->rollBack(); $errors[] = "Fout bij opslaan."; }
        }
    }

    if ($action_type === 'edit' && empty($errors)) {
        $res_id = $_POST['reservation_id'];
        $booked_slots = getBookedSlots($conn, $dates, $res_id);

        if (isBooked($date, $time, $booked_slots)) {
            $errors[] = "Dit slot is al bezet.";
        } else {
            try {
                $conn->beginTransaction();

                $check = $conn->prepare("SELECT id FROM reservations WHERE id = ? AND customer_id = ?");
                $check->execute([$res_id, $user_id]);
                if ($check->rowCount() == 0) throw new Exception("Geen toegang");

                $stmt = $conn->prepare("UPDATE reservations SET date = ?, time = ?, notes = ? WHERE id = ?");
                $stmt->execute([$date, $time, $notes, $res_id]);

                $conn->prepare("DELETE FROM reservation_products WHERE reservation_id = ?")->execute([$res_id]);
                $stmt_prod = $conn->prepare("INSERT INTO reservation_products (reservation_id, product_id) VALUES (?, ?)");
                foreach ($selected_products as $pid) $stmt_prod->execute([$res_id, $pid]);

                $conn->commit();
                header("Location: profile.php");
                exit;
            } catch (Exception $e) { $conn->rollBack(); $errors[] = "Fout bij wijzigen."; }
        }
    }
}

$t_user = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$t_user->execute([$user_id]);
$user_info = $t_user->fetch(PDO::FETCH_ASSOC);

$sql_list = "SELECT 
                reservations.id as res_id,
                reservations.date, reservations.time, reservations.notes, 
                products.name AS product_name, products.price
            FROM reservations
            LEFT JOIN reservation_products ON reservations.id = reservation_products.reservation_id
            LEFT JOIN products ON reservation_products.product_id = products.id
            WHERE reservations.customer_id = :uid
            ORDER BY reservations.date DESC, reservations.time ASC";
$t_res = $conn->prepare($sql_list);
$t_res->execute(['uid' => $user_id]);
$reservations = $t_res->fetchAll(PDO::FETCH_ASSOC);

$all_products = $conn->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

$edit_data = null;
$edit_product_ids = [];
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ? AND customer_id = ?");
    $stmt->execute([$edit_id, $user_id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($edit_data) {
        $stmt_p = $conn->prepare("SELECT product_id FROM reservation_products WHERE reservation_id = ?");
        $stmt_p->execute([$edit_id]);
        $edit_product_ids = $stmt_p->fetchAll(PDO::FETCH_COLUMN);
    }
}

$booked_create = getBookedSlots($conn, $dates);
$booked_edit = $edit_data ? getBookedSlots($conn, $dates, $edit_data['id']) : [];

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn Profiel - GRILLZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="includes/teeth.css">
</head>
<body class="bg-gray-100 font-sans text-gray-800 pt-24">

<?php require_once 'includes/navigation.php';?>

<div class="container mx-auto px-4 py-8">

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden mb-10">
        <div class="bg-gray-800 p-4 flex justify-between items-center">
            <h2 class="text-xl text-gold font-bold">Mijn Gegevens</h2>
            <a href="edit_profile.php" class="bg-gold text-gray-900 text-sm font-bold px-4 py-2 rounded hover:bg-yellow-500 transition">Wijzig</a>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="text-gray-500 text-xs font-bold uppercase">Naam</label><p class="text-lg"><?= htmlspecialchars($user_info['first_name'] . " " . $user_info['last_name']) ?></p></div>
            <div><label class="text-gray-500 text-xs font-bold uppercase">Email</label><p class="text-lg"><?= htmlspecialchars($user_info['email']) ?></p></div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Mijn Afspraken</h2>
            <a href="#new-booking" class="bg-gray-900 text-white font-bold px-4 py-2 rounded shadow hover:bg-gray-700 transition">+ Nieuwe Afspraak</a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <?php if (count($reservations) > 0): ?>
                <table class="min-w-full leading-normal">
                    <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs font-bold">
                        <th class="px-5 py-3 text-left">Datum & Tijd</th>
                        <th class="px-5 py-3 text-left">Product</th>
                        <th class="px-5 py-3 text-right"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reservations as $res): ?>
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="px-5 py-4">
                                <div class="font-bold"><?= htmlspecialchars($res['date']) ?></div>
                                <div class="text-gray-500"><?= htmlspecialchars($res['time']) ?></div>
                            </td>
                            <td class="px-5 py-4 text-yellow-600 font-bold"><?= htmlspecialchars($res['product_name'] ?? 'Custom') ?></td>
                            <td class="px-5 py-4 text-right">
                                <a href="profile.php?edit_id=<?= $res['res_id'] ?>#edit-booking" class="text-blue-600 underline mr-3">Wijzigen</a>
                                <a href="delete_reservation.php?id=<?= $res['res_id'] ?>" class="text-red-600 underline" onclick="return confirm('Annuleren?');">Annuleren</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500">Je hebt nog geen afspraken.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$show_create = (!empty($errors) && $_POST['action_type']=='create') || (isset($_GET['week_offset']) && !isset($_GET['edit_id']));
?>
<div id="new-booking" class="modal <?= $show_create ? 'show-modal' : '' ?>">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-2xl m-4 relative overflow-y-auto max-h-screen">
        <a href="profile.php" class="absolute top-4 right-4 text-gray-500 text-2xl">&times;</a>
        <h2 class="text-2xl font-bold mb-4">Nieuwe Grill</h2>

        <?php if (!empty($errors) && $_POST['action_type']=='create'): ?><div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= implode('<br>', $errors) ?></div><?php endif; ?>

        <form action="profile.php" method="POST">
            <input type="hidden" name="action_type" value="create">
            <input type="hidden" name="date" id="create_date">
            <input type="hidden" name="time" id="create_time">

            <div class="grill-wrapper">
                <div class="flex justify-between items-center w-full mb-4 px-2">
                    <a href="<?= getWeekUrl($week_offset - 1, 'new-booking') ?>" class="text-gold hover:text-white transition text-lg font-bold p-2 bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center">&#10094;</a>
                    <span class="text-gray-400 font-bold uppercase text-sm tracking-wider"><?= $week_label ?></span>
                    <a href="<?= getWeekUrl($week_offset + 1, 'new-booking') ?>" class="text-gold hover:text-white transition text-lg font-bold p-2 bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center">&#10095;</a>
                </div>

                <div class="day-labels"><?php foreach(['Ma','Di','Wo','Do','Vr','Za'] as $d) echo "<span>$d</span>"; ?></div>
                <div class="mouth">
                    <div class="jaw upper">
                        <?php foreach($dates as $i => $d):
                            $class = ($i==0||$i==5) ? 'canine' : 'incisor';
                            $booked = isBooked($d, $morning_time, $booked_create) ? 'booked' : '';
                            $past = ($d < date('Y-m-d')) ? 'past' : '';
                            ?>
                            <button type="button" class="tooth <?=$class?> <?=$booked?> <?=$past?>" <?=($booked||$past)?'disabled':''?> onclick="selectSlot(this, '<?=$d?>', '<?=$morning_time?>', 'create')"></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="jaw lower">
                        <?php foreach($dates as $i => $d):
                            $class = ($i==0||$i==5) ? 'canine' : 'incisor';
                            $booked = isBooked($d, $afternoon_time, $booked_create) ? 'booked' : '';
                            $past = ($d < date('Y-m-d')) ? 'past' : '';
                            ?>
                            <button type="button" class="tooth <?=$class?> <?=$booked?> <?=$past?>" <?=($booked||$past)?'disabled':''?> onclick="selectSlot(this, '<?=$d?>', '<?=$afternoon_time?>', 'create')"></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="legend">
                    <div><span class="dot" style="background:white"></span>Beschikbaar</div>
                    <div><span class="dot" style="background:#D4AF37"></span>Bezet</div>
                    <div><span class="dot" style="background:#00aaff"></span>Geselecteerd</div>
                </div>

                <p id="display_create" class="text-yellow-600 font-bold h-6 mt-2 text-sm"></p>
            </div>

            <div class="mt-4 mb-4">
                <label class="block font-bold mb-2">Behandeling</label>
                <div class="bg-gray-50 p-2 rounded border h-32 overflow-y-scroll">
                    <?php foreach($all_products as $prod): ?>
                        <label class="flex items-center mb-2 p-1">
                            <input type="checkbox" name="products[]" value="<?=$prod['id']?>" class="mr-2">
                            <?=$prod['name']?> (€<?=$prod['price']?>)
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <textarea name="notes" placeholder="Opmerking..." class="w-full border p-2 rounded mb-4"></textarea>
            <button type="submit" class="bg-gray-900 text-white font-bold py-2 px-6 rounded w-full">Reserveren</button>
        </form>
    </div>
</div>

<div id="edit-booking" class="modal <?= ($edit_data) ? 'show-modal' : '' ?>">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-2xl m-4 relative overflow-y-auto max-h-screen">
        <a href="profile.php" class="absolute top-4 right-4 text-gray-500 text-2xl">&times;</a>
        <h2 class="text-2xl font-bold mb-4">Wijzig Grill</h2>

        <?php if (!empty($errors) && $_POST['action_type']=='edit'): ?><div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= implode('<br>', $errors) ?></div><?php endif; ?>

        <?php if ($edit_data): ?>
            <form action="profile.php" method="POST">
                <input type="hidden" name="action_type" value="edit">
                <input type="hidden" name="reservation_id" value="<?= $edit_data['id'] ?>">
                <input type="hidden" name="date" id="edit_date" value="<?= $edit_data['date'] ?>">
                <input type="hidden" name="time" id="edit_time" value="<?= substr($edit_data['time'], 0, 5) ?>">

                <div class="grill-wrapper">
                    <div class="flex justify-between items-center w-full mb-4 px-2">
                        <a href="<?= getWeekUrl($week_offset - 1, 'edit-booking') ?>" class="text-gold hover:text-white transition text-lg font-bold p-2 bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center">&#10094;</a>
                        <span class="text-gray-400 font-bold uppercase text-sm tracking-wider"><?= $week_label ?></span>
                        <a href="<?= getWeekUrl($week_offset + 1, 'edit-booking') ?>" class="text-gold hover:text-white transition text-lg font-bold p-2 bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center">&#10095;</a>
                    </div>

                    <div class="day-labels"><?php foreach(['Ma','Di','Wo','Do','Vr','Za'] as $d) echo "<span>$d</span>"; ?></div>
                    <div class="mouth">
                        <div class="jaw upper">
                            <?php foreach($dates as $i => $d):
                                $class = ($i==0||$i==5) ? 'canine' : 'incisor';
                                $booked = isBooked($d, $morning_time, $booked_edit) ? 'booked' : '';
                                $past = ($d < date('Y-m-d')) ? 'past' : '';
                                $selected = ($edit_data['date'] == $d && substr($edit_data['time'], 0, 5) == substr($morning_time, 0, 5)) ? 'selected' : '';
                                ?>
                                <button type="button" class="tooth <?=$class?> <?=$booked?> <?=$past?> <?=$selected?>" <?=($booked||$past)?'disabled':''?> onclick="selectSlot(this, '<?=$d?>', '<?=$morning_time?>', 'edit')"></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="jaw lower">
                            <?php foreach($dates as $i => $d):
                                $class = ($i==0||$i==5) ? 'canine' : 'incisor';
                                $booked = isBooked($d, $afternoon_time, $booked_edit) ? 'booked' : '';
                                $past = ($d < date('Y-m-d')) ? 'past' : '';
                                $selected = ($edit_data['date'] == $d && substr($edit_data['time'], 0, 5) == substr($afternoon_time, 0, 5)) ? 'selected' : '';
                                ?>
                                <button type="button" class="tooth <?=$class?> <?=$booked?> <?=$past?> <?=$selected?>" <?=($booked||$past)?'disabled':''?> onclick="selectSlot(this, '<?=$d?>', '<?=$afternoon_time?>', 'edit')"></button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="legend">
                        <div><span class="dot" style="background:white"></span>Beschikbaar</div>
                        <div><span class="dot" style="background:#D4AF37"></span>Bezet</div>
                        <div><span class="dot" style="background:#00aaff"></span>Huidige</div>
                    </div>

                    <p id="display_edit" class="text-yellow-600 font-bold h-6 mt-2 text-sm">Huidig: <?= $edit_data['date'] ?> (<?= substr($edit_data['time'], 0, 5) ?>)</p>
                </div>

                <div class="mt-4 mb-4">
                    <label class="block font-bold mb-2">Behandeling</label>
                    <div class="bg-gray-50 p-2 rounded border h-32 overflow-y-scroll">
                        <?php foreach($all_products as $prod): $checked = in_array($prod['id'], $edit_product_ids) ? 'checked' : ''; ?>
                            <label class="flex items-center mb-2 p-1">
                                <input type="checkbox" name="products[]" value="<?=$prod['id']?>" <?=$checked?> class="mr-2">
                                <?=$prod['name']?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <textarea name="notes" class="w-full border p-2 rounded mb-4"><?= htmlspecialchars($edit_data['notes']) ?></textarea>
                <button type="submit" class="bg-gray-900 text-white font-bold py-2 px-6 rounded w-full">Wijzigingen Opslaan</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php';?>

<script>
    function selectSlot(btn, date, time, prefix) {
        const wrapper = btn.closest('.grill-wrapper');
        wrapper.querySelectorAll('.tooth').forEach(t => t.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById(prefix + '_date').value = date;
        document.getElementById(prefix + '_time').value = time;
        document.getElementById('display_' + prefix).innerText = `Geselecteerd: ${date} (${time.substring(0,5)})`;
    }
</script>

</body>
</html>