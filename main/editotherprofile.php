<?php

// get includes (adminauth and db)
require_once 'includes/adminauth.php';
require_once 'includes/database';

// If isset post;
if (isset($_POST)) {
    // get stuff from post for postback (which should include id)
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $id = $_POST['id'];
    $email = $_POST['email'];
    $usertype = $_POST['usertype'];
    // do error checks
    
    // if no errors;
        // prepare UPDATE query
        // execute UPDATE query
        // reload page and hand back id using GET
    // if errors;
        // prepare errors to show in the form
}
// If isset get
    // get ID from GET
    // prepare query to fetch info
    // execute query to fetch info
// if neither get or post are set, send user back to admininterface



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
