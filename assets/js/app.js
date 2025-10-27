document.addEventListener('DOMContentLoaded', () => {
    setupPdfTree(); // setup listenerů

    // vybrání posledního souboru
    const fileTreeContainer = document.getElementById('fileTreeForUpdate');
    const lastFilePath = fileTreeContainer.getAttribute('data-last-file');
    if (lastFilePath) {
        const lastItem = fileTreeContainer.querySelector(`.pdf-item[data-path="${lastFilePath}"]`);
        if (lastItem) lastItem.click();
    }
});

// Funkce pro nahrání PDF souboru
function uploadPdf() {
    const formData = new FormData();
    const fileInput = document.querySelector('#pdfFile');

    if (!fileInput.files.length) {
        alert("Prosím vyberte soubor.");
        return;
    }

    formData.append('pdfFile', fileInput.files[0]);

    const uploadMessage = document.querySelector('#uploadMessage');
    uploadMessage.innerHTML = "Nahrávám soubor...";

    fetch('/includes/upload.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json()) // Zpracování odpovědi jako JSON
        .then(data => {
            uploadMessage.innerHTML = data.message;

            if (data.status === 'success') {
                const fileTreeContainer = document.querySelector('#fileTreeForUpdate');
                if (fileTreeContainer) {
                    fileTreeContainer.innerHTML = data.fileTreeForUpdate;

                    // znovu setup listenerů na strom
                    setupPdfTree();

                    // automaticky kliknout na nově nahraný soubor
                    if (data.newFilePath) {
                        const newItem = fileTreeContainer.querySelector(`.pdf-item[data-path="${data.newFilePath}"]`);
                        if (newItem) newItem.click();
                    }
                }

                // Zavřít modal po úspěšném nahrání
                modal.style.display = 'none';
                // Vyčistit input
                fileInput.value = '';
                selectedFileName.textContent = '';
            }

        }).catch(error => {
        uploadMessage.innerHTML = "Došlo k chybě při nahrávání souboru.";
        console.error(error);
    });
}

const fileInput = document.getElementById('pdfFile');
const selectedFileName = document.getElementById('selectedFileName');

fileInput.addEventListener('change', () => {
    if (fileInput.files.length) {
        selectedFileName.textContent = fileInput.files[0].name;
    } else {
        selectedFileName.textContent = '';
    }
});


let activePdfItem = null;

function setupPdfTree() {
    document.querySelectorAll('.pdf-item').forEach(item => {
        item.addEventListener('click', function () {
            // otevře PDF
            const path = this.getAttribute('data-path');
            document.getElementById('pdfViewer').src = path;

            // odznačí předchozí
            if (activePdfItem) {
                activePdfItem.classList.remove('active-pdf');
            }

            // označí aktuální
            activePdfItem = this;
            activePdfItem.classList.add('active-pdf');
        });
    });
}

// zavoláme po načtení stránky a po reloadu stromu
document.addEventListener('DOMContentLoaded', setupPdfTree);


function sharePdf() {
    if (navigator.share) {
        navigator.share({
            title: 'Důležité PDF',
            text: 'Podívejte se na tento soubor PDF.',
            url: 'uploads/sds/3267636208.pdf'
        }).then(() => {
            console.log('Sdílení bylo úspěšné');
        }).catch((error) => {
            console.error('Chyba při sdílení:', error);
        });
    } else {
        // Pokud je sdílení na zařízení nepodporováno
        alert('Sdílení není podporováno na tomto zařízení.');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('a[href^="uploads/"]'); // Najde všechny odkazy začínající na 'uploads/'

    links.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Zamezí výchozímu chování odkazu (otevření nové stránky)
            openPdf(this.getAttribute('href')); // Otevře PDF v iframe
        });
    });
});

// Funkce pro otevření PDF v iframe
function openPdf(filePath) {
    const iframe = document.getElementById('pdfViewer'); // Získá iframe podle id
    if (iframe) {
        iframe.src = filePath; // Nastaví zdroj (src) iframe na cestu k souboru
    }
}

document.querySelectorAll('.sort-column').forEach(col => {
    col.addEventListener('click', () => {
        const type = col.getAttribute('data-type');
        let order = col.getAttribute('data-order');

        // cyklus: none -> asc -> desc -> asc
        if (order === 'none') order = 'asc';
        else if (order === 'asc') order = 'desc';
        else order = 'asc';

        // reset ostatních sloupců
        document.querySelectorAll('.sort-column').forEach(c => {
            if (c !== col) {
                c.setAttribute('data-order', 'none');
                c.querySelector('.asc').style.color = '#ccc';
                c.querySelector('.desc').style.color = '#ccc';
            }
        });

        // nastav barvy šipek
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

        // přesuň soubory ve správném pořadí
        files.forEach(f => ul.appendChild(f));
    });
});


const uploadBtn = document.getElementById('uploadBtn');
const modal = document.getElementById('uploadModal');
const closeBtn = document.querySelector('.modal .close');

uploadBtn.addEventListener('click', () => {
    modal.style.display = 'flex'; // otevře modal
});

closeBtn.addEventListener('click', () => {
    modal.style.display = 'none'; // zavře modal
});

// Zavření po kliknutí mimo obsah
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

