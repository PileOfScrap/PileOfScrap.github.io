<?php
    session_start();

?>
!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRILLAZ - Custom Jewelry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="includes/styles.css">
</head>
<body>
    <?php
        require_once 'includes/navigation.php';
        require_once 'includes/bestsellers.php';
    ?>

    <?php require_once 'includes/footer.php'; ?>
</body>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    gold: '#D4AF37',
                    'gold-dark': '#B5952F',
                }
            }
        }
    }
</script>
