<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/functions.php';

$baseDir = __DIR__ . '/uploads';
$lastFilePath = null;
$fileTreeHtml = renderDirectory($baseDir, 'uploads', $lastFilePath);

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>TRUST</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/app.js" defer></script>
</head>
<body>
<?php include('includes/header.php'); ?>


<main>
    <div id="fileTree">
        <button id="uploadBtn" title="Nahrát PDF">+</button>

        <div id="fileSort" class="file-sort">
            <div class="sort-column" data-type="name" data-order="none">
                <span style="margin-left: 8px">Název</span>
                <span class="sort-arrows">
                    <span class="asc">&#9650;</span>
                    <span class="desc">&#9660;</span>
                </span>
            </div>
            <div class="sort-column" data-type="date" data-order="none">
                <span>Datum</span>
                <span class="sort-arrows">
                    <span class="asc">&#9650;</span>
                    <span class="desc">&#9660;</span>
                </span>
            </div>
        </div>

        <div id="fileTreeForUpdate" data-last-file="<?= htmlspecialchars($lastFilePath) ?>">
            <?= $fileTreeHtml ?>
        </div>

    </div>

    <div id="pdfContainer">
        <iframe id="pdfViewer" src=""></iframe>
        <button id="shareBtn" onclick="sharePdf()" title="Sdílet PDF">&#x1F517;</button>
    </div>
</main>

<div id="uploadModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Nahrát PDF soubor</h3>

        <div class="file-input-container">
            <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf" required>
            <label for="pdfFile">Vyber soubor</label>
            <span id="selectedFileName"></span>
        </div>

        <button id="modalUploadBtn" type="button" onclick="uploadPdf()" title="Nahrát">&#10003;</button>
        <p id="uploadMessage"></p>
    </div>
</div>


</body>
</html>
