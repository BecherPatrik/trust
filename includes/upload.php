<?php
header('Content-Type: application/json');

define('UPLOAD_DIR', __DIR__ . '/../uploads');

$response = [
    'status' => 'error',
    'message' => 'Nahrávání selhalo.'
];

if (isset($_FILES['pdfFile']) && $_FILES['pdfFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['pdfFile']['tmp_name'];
    $fileName = basename($_FILES['pdfFile']['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExt !== 'pdf') {
        $response['message'] = 'Můžete nahrát pouze PDF soubory.';
        echo json_encode($response);
        exit;
    }

    $safeName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $fileName);
    $destination = UPLOAD_DIR . DIRECTORY_SEPARATOR . $safeName;

    // Pokud soubor existuje, přidej timestamp
    if (file_exists($destination)) {
        $nameOnly = pathinfo($safeName, PATHINFO_FILENAME);
        $destination = UPLOAD_DIR . DIRECTORY_SEPARATOR . $nameOnly . '_' . time() . '.pdf';
        $safeName = basename($destination);
    }

    if (move_uploaded_file($fileTmpPath, $destination)) {
        $fileDate = date("Y-m-d H:i", filemtime($destination));
        $response = [
            'status' => 'success',
            'message' => 'Soubor byl úspěšně nahrán.',
            'newFileName' => $safeName,
            'newFilePath' => 'uploads/' . $safeName,
            'newFileDate' => $fileDate
        ];
    } else {
        $response['message'] = 'Nepodařilo se uložit soubor.';
    }
} else {
    $response['message'] = 'Nebyl vybrán žádný soubor nebo nastala chyba při nahrávání.';
}

echo json_encode($response);
