<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/functions.php';

// --- Generování stromu PDF ---
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
    <?php
    // --- Předání dat do panelu stromu ---
    $panelData = [
            'lastFilePath' => $lastFilePath,
            'fileTreeHtml' => $fileTreeHtml
    ];
    include('includes/pdf-tree-panel.php');

    // --- PDF viewer panel ---
    include('includes/pdf-viewer-panel.php');
    ?>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/bottom.php'; ?>
</body>
</html>
