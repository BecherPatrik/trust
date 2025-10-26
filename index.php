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


<!-- Formulář pro nahrání PDF -->
<h2>Nahrát PDF soubor</h2>
<form id="uploadForm" enctype="multipart/form-data">
    <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf" required>
    <button type="button" onclick="uploadPdf()">Nahrát</button>
</form>
<p id="uploadMessage"></p>

<h2>Seznam PDF souborů</h2>
<div id="fileTree">
    <!-- Tento element bude aktualizován dynamicky po nahrání souboru -->
    <?php
    $baseDir = __DIR__ . '/uploads'; // složka, kde máš PDF
    echo renderDirectory($baseDir, 'uploads');
    ?>
</div>


<hr>
<h2>Náhled PDF</h2>
<iframe id="pdfViewer" src="" width="100%" height="600px"></iframe>
<!--<button onclick="sharePdf()">Sdílet PDF</button>-->

</body>
</html>
