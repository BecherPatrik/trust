<?php
header('Content-Type: application/json; charset=utf-8');

define('UPLOAD_DIR', __DIR__ . '/../uploads');

$response = [
    'status' => 'error',
    'message' => 'Nahrávání selhalo.'
];

if (isset($_FILES['pdfFile']) && $_FILES['pdfFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['pdfFile']['tmp_name'];
    $fileName = $_FILES['pdfFile']['name']; // plný název, nic neupravujeme
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExt !== 'pdf') {
        $response['message'] = 'Můžete nahrát pouze PDF soubory.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Bezpečné spojení cesty – nijak neupravujeme název
    $destination = UPLOAD_DIR . DIRECTORY_SEPARATOR . $fileName;

    // Pokud soubor existuje, přidáme timestamp
    if (file_exists($destination)) {
        $nameOnly = pathinfo($fileName, PATHINFO_FILENAME);
        $destination = UPLOAD_DIR . DIRECTORY_SEPARATOR . $nameOnly . '_' . time() . '.pdf';
        $fileName = basename($destination);
    }

    if (move_uploaded_file($fileTmpPath, $destination)) {
        $fileDate = date("Y-m-d H:i", filemtime($destination));
        $response = [
            'status' => 'success',
            'message' => 'Soubor byl úspěšně nahrán.',
            'newFileName' => $fileName,
            'newFilePath' => 'uploads/' . $fileName,
            'newFileDate' => $fileDate
        ];
    } else {
        $response['message'] = 'Nepodařilo se uložit soubor.';
    }
} else {
    $response['message'] = 'Nebyl vybrán žádný soubor nebo nastala chyba při nahrávání.';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
