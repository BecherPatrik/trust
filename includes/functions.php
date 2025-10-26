<?php
function renderDirectory($dir, $relative = '') {
    $items = scandir($dir);
    sort($items, SORT_NATURAL | SORT_FLAG_CASE);

    $html = "<ul class='pdf-tree'>";
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = $relative . '/' . $item;

        if (is_dir($path)) {
            $html .= '<li><strong>' . htmlspecialchars($item) . '</strong>';
            $html .= renderDirectory($path, $relativePath);
            $html .= '</li>';
        } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'pdf') {
            $fileDate = date("Y-m-d H:i", filemtime($path));
            $html .= '<li onclick="openPdf(\'' . htmlspecialchars($relativePath) . '\');">
                        <a href="#">' . htmlspecialchars($item) . '</a>
                        <span class="file-date">' . $fileDate . '</span>
                      </li>';
        }
    }
    $html .= "</ul>";
    return $html;
}
?>
