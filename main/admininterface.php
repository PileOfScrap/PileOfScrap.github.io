<?php
// make sure to include adminauth.php
require_once 'includes/adminauth.php';
require_once 'includes/database.php';

// set query to select *
$query = '
SELECT 
    user_id,
    first_name,
    last_name,
    user_type,
    email,
    phone
FROM customers';
// dont select *, select all but password :facepalm:

// Check if post isset
if (isset($_POST)) {

    // if input isset
    if (isset($_POST['input'])) {
        // get input from POST
        $input = $_POST['input'];
        // append WHERE first_name = $input OR WHERE last_name = $input to query
        $query .= ' WHERE first_name = $input OR WHERE last_name = $input';
    }
    // if ordertype isset
    if (isset($_POST['ordertype'])) {
        // get ordertype from POST
        $order = $_POST['order'];
        // append ORDER BY first_name or ORDER BY last_name depending on what is set
        $query .= ' ORDER BY $order';
        // ($order should be either first_name or last_name depending on what is selected)
    }
}

// Execute query
$result = mysqli_query($database, $query);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

</body>
</html>
// (in html) extract query result in table.
<h1>header</h1>
<p>webby wo wa be doh</p>

<form>
    <label for="input">Zoek naar voornaam/achternaam (hoofdlettergevoelig)</label>
    <input type="text" <?if (isset($input)) {echo 'content=\"$input\"';};?> name="input" id="input">

    <p>Sorteer op:</p>
    <label for="first_name">Voornaam</label>
    <input type="radio" name="order" id="first_name" value="first_name">

    <label for="last_name">Achternaam</label>
    <input type="radio" name="order" id="last_name" value="last_name">
</form>

<table>
    <thead>
        <th>gebruikers id</th>
        <th>Voornaam</th>
        <th>Achternaam</th>
        <th>E-mailadres</th>
        <th>Telefoonnummer</th>
        <th>Gebruikerstype</th>
        <th>Goto</th>
    </thead>
    <tbody>
        <?php  while ($user = mysqli_fetch_assoc($result)) {
            ?><tr>
                <td><?=htmlentities($user['user_id'])?></td>
            </tr>
            <tr>
                <td><?=htmlentities($user['first_name'])?></td>
            </tr>
            <tr>
                <td><?=htmlentities($user['last_name'])?></td>
            </tr>
            <tr>
                <td><?=htmlentities($user['email'])?></td>
            </tr>
            <tr>
                <td><?=htmlentities($user['phone'])?></td>
            </tr>
            <tr>
                <td><?switch ($user['user_type']) {
                        case 0:
                            echo 'Klant';
                            break;
                        case 1:
                            echo 'Medewerker';
                            break;
                        case 2:
                            echo 'Admin';
                            break;
                    }?></td>
            </tr>
            <tr>
                <a href='editotherprofile.php?id=<?=$user['user_id']?>'>Edit</a>
            </tr>
        <?}?>

    </tbody>
</table>