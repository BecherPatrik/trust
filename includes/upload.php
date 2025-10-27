<?php

// Zobrazování chyb
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/functions.php';

// Cesta pro uložení souboru
$uploadDir = __DIR__ . '/../uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Zpracování souboru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdfFile'])) {
    $file = $_FILES['pdfFile'];
    $fileName = basename($file['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileType === 'pdf') {
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo json_encode([
                'status' => 'success',
                'message' => "Soubor $fileName byl úspěšně nahrán.",
                'fileTreeForUpdate' => renderDirectory($uploadDir, 'uploads'),
                'newFilePath' => 'uploads/' . $fileName // přidáme cestu k novému souboru
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Chyba při nahrávání souboru.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Povolené jsou pouze PDF soubory.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Nebyl odeslán žádný soubor.'
    ]);
}

