<?php
// Funkce pro generování stromu souborů
function renderDirectory($dir, $relative = '') {
    $items = scandir($dir);
    $html = "<ul>";
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = $relative . '/' . $item;

        if (is_dir($path)) {
            $html .= '<li><strong>' . htmlspecialchars($item) . '</strong>';
            $html .= renderDirectory($path, $relativePath);
            $html .= '</li>';
        } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'pdf') {
            $html .= '<li><a href="#" onclick="openPdf(\'' . htmlspecialchars($relativePath) . '\'); return false;">' . htmlspecialchars($item) . '</a></li>';
        }
    }
    $html .= "</ul>";
    return $html;
}
