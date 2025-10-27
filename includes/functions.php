<?php
define('UPLOAD_DIR', __DIR__ . '/../uploads'); // centrální definice upload složky

/**
 * Vytvoří HTML strom adresářů a PDF souborů
 *
 * @param string $dir Fyzická cesta k adresáři
 * @param string $relative Relativní cesta pro odkazy
 * @param string|null $lastFilePath Reference na poslední soubor (nejnovější)
 * @return string HTML strom
 */
function renderDirectory($dir = UPLOAD_DIR, $relative = '', &$lastFilePath = null) {
    if (!is_dir($dir)) return '';

    $items = scandir($dir, SCANDIR_SORT_NONE);

    // Nejprve seřadíme soubory podle data (nejnovější nahoře)
    usort($items, function($a, $b) use ($dir) {
        $pathA = $dir . DIRECTORY_SEPARATOR . $a;
        $pathB = $dir . DIRECTORY_SEPARATOR . $b;

        $timeA = is_file($pathA) ? filemtime($pathA) : 0;
        $timeB = is_file($pathB) ? filemtime($pathB) : 0;

        return $timeB <=> $timeA;
    });

    $html = "<ul class='pdf-tree'>";

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = $relative ? $relative . '/' . $item : $item;

        if (is_dir($path)) {
            $html .= '<li><strong>' . htmlspecialchars($item) . '</strong>';
            $html .= renderDirectory($path, $relativePath, $lastFilePath);
            $html .= '</li>';
        } elseif (is_file($path) && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf') {
            if (!file_exists($path)) continue;
            $fileDate = date("Y-m-d H:i", filemtime($path));

            if (!$lastFilePath) $lastFilePath = $relativePath;

            $html .= '<li class="pdf-item" data-path="' . htmlspecialchars($relativePath) . '">
                        <span class="file-name">' . htmlspecialchars($item) . '</span>
                        <span class="file-date">' . $fileDate . '</span>
                      </li>';
        }
    }

    $html .= "</ul>";
    return $html;
}

/**
 * Funkce pro bezpečný output HTML
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
