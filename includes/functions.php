<?php

function renderDirectory($dir, $relative = '', &$lastFilePath = null) {
    $items = scandir($dir);
    sort($items, SORT_NATURAL | SORT_FLAG_CASE);

    $html = "<ul class='pdf-tree'>";
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = $relative ? $relative . '/' . $item : $item;

        if (is_dir($path)) {
            $html .= '<li><strong>' . htmlspecialchars($item) . '</strong>';
            $html .= renderDirectory($path, $relativePath, $lastFilePath);
            $html .= '</li>';
        } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'pdf') {
            $fileDate = date("Y-m-d H:i", filemtime($path));

            if (!$lastFilePath || filemtime($path) > filemtime($lastFilePath)) {
                $lastFilePath = $relativePath; // tady relativní cesta
            }

            $html .= '<li class="pdf-item" data-path="' . htmlspecialchars($relativePath) . '">
                        <span class="file-name">' . htmlspecialchars($item) . '</span>
                        <span class="file-date">' . $fileDate . '</span>
                      </li>';
        }
    }
    $html .= "</ul>";
    return $html;
}
?>
