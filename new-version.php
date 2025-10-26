<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/functions.php';
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


        <?php
        $baseDir = __DIR__ . '/uploads';
        echo renderDirectory($baseDir, 'uploads');
        ?>
    </div>

    <div id="pdfContainer">
        <iframe id="pdfViewer" src=""></iframe>
    </div>
</main>

<script>
    document.addEventListener('click', function (e) {
        if (e.target.tagName === 'STRONG') {
            e.target.parentElement.classList.toggle('open');
        }
    });

    function openPdf(path) {
        const pdfViewer = document.getElementById('pdfViewer');
        pdfViewer.src = path;
    }
</script>
</body>
</html>
