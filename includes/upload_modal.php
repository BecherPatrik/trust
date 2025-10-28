<div id="uploadModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Nahrát PDF soubor</h3>

        <div class="file-input-container">
            <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf" required>
            <label for="pdfFile">Vyber soubor</label>
            <span id="selectedFileName"></span>
        </div>

        <button id="modalUploadBtn"  type="button" title="Nahrát">&#10003;</button>
        <p id="uploadMessage"></p>
    </div>
</div>
