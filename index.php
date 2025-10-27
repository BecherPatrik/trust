<?php
// Zobrazování chyb (pro vývoj)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Zahrnutí souboru pro připojení k databázi
//require_once 'includes/db.php';
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>TRUST</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Načítání CSS -->
    <script src="assets/js/app.js" defer></script> <!-- Načítání JS -->
</head>
<body>
<?php include('includes/header.php'); ?>

<h1>Vítejte na domovské stránce!</h1>
<p>Stránka domů, kde můžeš zobrazit základní obsah.</p>

<?php //include('includes/db.php'); ?>

</body>
</html>
