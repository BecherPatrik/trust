<?php
// Zobraz chyby (pouze pro vývoj)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Nastavení cesty k uploadům
$uploadDir = realpath(__DIR__ . '/../uploads') . DIRECTORY_SEPARATOR;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file'])) {
    $fileUrl = $_POST['file'];

    // Extrahuj pouze relativní část cesty za "uploads/"
    $parts = explode('uploads/', $fileUrl);
    if (count($parts) < 2) {
        echo "Neplatný požadavek.";
        exit;
    }

    $relativePath = $parts[1];
    $filePath = realpath($uploadDir . $relativePath);

    // Bezpečnostní kontrola: soubor musí být v rámci složky uploads
    if ($filePath === false || strpos($filePath, $uploadDir) !== 0) {
        echo "Neplatná cesta k souboru.";
        exit;
    }

    // Kontrola existence a pokus o smazání
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo "Soubor byl úspěšně smazán.";
        } else {
            echo "Nepodařilo se smazat soubor.";
        }
    } else {
        echo "Soubor neexistuje.";
    }
} else {
    echo "Neplatný požadavek.";
}
