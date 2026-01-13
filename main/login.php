<?php
require_once 'includes/db.php';
session_start();

$error = '';

if (isset($_POST['submit'])) {
    // Read input
    $username = trim($_POST['user'] ?? '');
    $password = $_POST['pass'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please fill in both fields';
    } else {
        // Escape username to prevent SQL injection
        $username_safe = mysqli_real_escape_string($db, $username);

        $query = "
            SELECT
                user_id,
                user_name,
                user_pass
            FROM users
            WHERE user_name = '$username_safe'
            LIMIT 1
        ";

        $result = mysqli_query($db, $query);

        if (!$result) {
            exit('Query error: ' . mysqli_error($db));
        }

        $userdat = mysqli_fetch_assoc($result);

        // Check if user exists
        if (!$userdat) {
            $error = 'Invalid username or password';
        } else {
            // Verify hashed password
            if (password_verify($password, $userdat['user_pass'])) {
                // Successful login
                $_SESSION['user_id'] = (int)$userdat['user_id'];
                $_SESSION['username'] = $userdat['user_name'];


                header('Location: index.php');
                exit();
            } else {
                $error = 'Invalid username or password';
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<?php if ($error !== '') { ?>
    <p><?= htmlentities($error) ?></p>
<?php }; ?>

<form method="post">
    <p>
        <label>Username</label><br>
        <input type="text" name="user" required>
    </p>

    <p>
        <label>Password</label><br>
        <input type="password" name="pass" required>
    </p>

    <button type="submit" name="submit">Log in</button>
</form>

<p><a href="index.php">Back to overview</a></p>

</body>
</html>