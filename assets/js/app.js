document.addEventListener('DOMContentLoaded', () => {
    // === MODAL ===
    const modal = document.getElementById('uploadModal');
    const uploadBtn = document.getElementById('uploadBtn');
    const closeBtn = modal.querySelector('.close');
    const modalUploadBtn = document.getElementById('modalUploadBtn');

    uploadBtn.addEventListener('click', () => { modal.style.display = 'flex'; });
    closeBtn.addEventListener('click', () => { modal.style.display = 'none'; });
    window.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

    // === FILE INPUT ===
    const fileInput = document.getElementById('pdfFile');
    const selectedFileName = document.getElementById('selectedFileName');
    fileInput.addEventListener('change', () => {
        selectedFileName.textContent = fileInput.files.length ? fileInput.files[0].name : '';
    });

    // === UPLOAD PDF ===
    modalUploadBtn.addEventListener('click', uploadPdf);

    // === PDF TREE ===
    setupPdfTree();

    // Automaticky vybrat poslední soubor
    const fileTreeContainer = document.getElementById('fileTreeForUpdate');
    const lastFilePath = fileTreeContainer?.getAttribute('data-last-file');
    if (lastFilePath) {
        const lastItem = fileTreeContainer.querySelector(`.pdf-item[data-path="${lastFilePath}"]`);
        if (lastItem) lastItem.click();
    }

    // Nastav výchozí řazení podle data (desc)
    const dateColumn = document.querySelector('.sort-column[data-type="date"]');
    if (dateColumn) {
        dateColumn.setAttribute('data-order', 'desc');
        const ascArrow = dateColumn.querySelector('.asc');
        const descArrow = dateColumn.querySelector('.desc');
        if (ascArrow) ascArrow.style.color = '#ccc';
        if (descArrow) descArrow.style.color = '#0066cc';
    }

    // === LINKY NA PDF ===
    document.querySelectorAll('a[href^="uploads/"]').forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            openPdf(link.getAttribute('href'));
        });
    });

    // === SORT ===
    document.querySelectorAll('.sort-column').forEach(col => {
        col.addEventListener('click', () => {
            const type = col.getAttribute('data-type');
            let order = col.getAttribute('data-order');

            // cyklus: none -> asc -> desc -> asc
            order = order === 'none' ? 'asc' : order === 'asc' ? 'desc' : 'asc';

            // reset ostatních sloupců
            document.querySelectorAll('.sort-column').forEach(c => {
                if (c !== col) {
                    c.setAttribute('data-order', 'none');
                    c.querySelector('.asc').style.color = '#ccc';
                    c.querySelector('.desc').style.color = '#ccc';
                }
            });

            // nastavit barvy šipek
            const ascArrow = col.querySelector('.asc');
            const descArrow = col.querySelector('.desc');
            if (order === 'asc') {
                ascArrow.style.color = '#0066cc';
                descArrow.style.color = '#ccc';
            } else {
                ascArrow.style.color = '#ccc';
                descArrow.style.color = '#0066cc';
            }

            col.setAttribute('data-order', order);

            // seřadit pouze soubory
            const ul = document.querySelector('#fileTreeForUpdate ul.pdf-tree');
            const files = Array.from(ul.querySelectorAll('.pdf-item'));

            files.sort((a, b) => {
                let cmp = 0;
                if (type === 'name') {
                    cmp = a.querySelector('.file-name').textContent.localeCompare(b.querySelector('.file-name').textContent);
                } else if (type === 'date') {
                    const dateA = new Date(a.querySelector('.file-date').textContent);
                    const dateB = new Date(b.querySelector('.file-date').textContent);
                    cmp = dateA - dateB;
                }
                return order === 'asc' ? cmp : -cmp;
            });

            files.forEach(f => ul.appendChild(f));
        });
    });
});

// === FUNKCE ===

// Nahrání PDF
function uploadPdf() {
    const fileInput = document.getElementById('pdfFile');
    if (!fileInput.files.length) return alert("Prosím vyberte soubor.");

    const formData = new FormData();
    formData.append('pdfFile', fileInput.files[0]);

    const uploadMessage = document.getElementById('uploadMessage');
    uploadMessage.textContent = "Nahrávám soubor...";

    fetch('includes/upload.php', { method: 'POST', body: formData })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);

                uploadMessage.textContent = data.message;

                if (data.status === 'success' && data.newFilePath) {
                    const fileTreeContainer = document.querySelector('#fileTreeForUpdate ul.pdf-tree');
                    const li = document.createElement('li');
                    li.className = 'pdf-item';
                    li.setAttribute('data-path', data.newFilePath);
                    li.innerHTML = `
                        <span class="file-name">${data.newFileName}</span>
                        <span class="file-date">${data.newFileDate}</span>
                    `;

                    // vložíme na začátek stromu (nejnovější nahoře)
                    fileTreeContainer.prepend(li);

                    // znovu setup listenerů pro nově přidaný soubor
                    setupPdfTree();

                    // automaticky kliknout na nově nahraný soubor
                    li.click();

                    // zavřít modal
                    document.getElementById('uploadModal').style.display = 'none';
                    // reset inputu
                    fileInput.value = '';
                    selectedFileName.textContent = '';
                }

            } catch (e) {
                console.error("Neplatný JSON z upload.php:", text);
                uploadMessage.textContent = "Chyba při nahrávání (neplatná odpověď serveru).";
            }
        })
        .catch(err => {
            console.error(err);
            uploadMessage.textContent = "Chyba při nahrávání souboru.";
        });
}

// Otevření PDF
function openPdf(filePath) {
    const iframe = document.getElementById('pdfViewer');
    if (iframe) iframe.src = filePath;
}

// PDF strom
let activePdfItem = null;
function setupPdfTree() {
    document.querySelectorAll('.pdf-item').forEach(item => {
        item.addEventListener('click', function () {
            const path = this.getAttribute('data-path');
            openPdf(path);

            if (activePdfItem) activePdfItem.classList.remove('active-pdf');
            activePdfItem = this;
            activePdfItem.classList.add('active-pdf');
        });
    });
}

// Sdílení PDF
function sharePdf() {
    if (navigator.share) {
        navigator.share({
            title: 'Důležité PDF',
            text: 'Podívejte se na tento soubor PDF.',
            url: document.getElementById('pdfViewer').src
        }).then(() => console.log('Sdílení bylo úspěšné'))
            .catch((error) => console.error('Chyba při sdílení:', error));
    } else {
        alert('Sdílení není podporováno na tomto zařízení.');
    }
}
