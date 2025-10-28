<?php
// Tento panel zobrazuje PDF náhled a tlačítko pro sdílení
?>
<div id="pdfContainer">
    <iframe id="pdfViewer"
            src=""></iframe>

    <!-- Nové tlačítko pro otevření PDF -->
    <button id="openPdfBtn" class="pdfButton" title="Otevřít PDF v novém tabu">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="6" stroke-linecap="butt" stroke-linejoin="miter">
            <line x1="6" y1="18" x2="18" y2="6"/>
            <polyline points="6,6 18,6 18,18"/>
        </svg>
    </button>


    <!-- Sdílecí tlačítko -->
    <button id="shareBtn" class="pdfButton" title="Sdílet PDF">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="6" stroke-linecap="butt" stroke-linejoin="miter">
            <g transform="rotate(135 12 12)">
                <line x1="6" y1="18" x2="18" y2="6"/>
                <polyline points="6,6 18,6 18,18"/>
            </g>
        </svg>
    </button>
</div>