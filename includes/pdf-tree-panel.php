<?php
// Tento panel zobrazuje strom PDF a tlačítko pro upload

// Očekává proměnnou $panelData s klíči:
// 'fileTreeHtml' => HTML stromu
// 'lastFilePath' => cesta k poslednímu souboru
extract($panelData);
?>

<div id="fileTree">
    <!-- Tlačítko pro upload PDF -->
    <button id="uploadBtn" title="Nahrát PDF">+</button>

    <!-- Panel pro řazení souborů -->
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

    <!-- Scrollovatelný panel se stromem PDF -->
    <div class="tree-scroll">
        <div id="fileTreeForUpdate" data-last-file="<?= htmlspecialchars($lastFilePath) ?>">
            <?= $fileTreeHtml ?>
        </div>
    </div>
</div>
