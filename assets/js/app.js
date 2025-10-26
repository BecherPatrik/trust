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
            uploadMessage.innerHTML = data.message; // Zobrazí zprávu o nahrávání

            if (data.status === 'success') {
                // Aktualizace stromu souborů
                const fileTreeContainer = document.querySelector('#fileTree');
                if (fileTreeContainer) {
                    fileTreeContainer.innerHTML = data.fileTree; // Zobrazí nový strom souborů
                }
            }
        })
        .catch(error => {
            uploadMessage.innerHTML = "Došlo k chybě při nahrávání souboru.";
            console.error(error);
        });
}



function updateFileTree() {
    fetch('/includes/renderTree.php')
        .then(response => response.text())
        .then(data => {
            const fileTreeContainer = document.querySelector('#fileTree');
            fileTreeContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Chyba při aktualizaci stromu souborů:', error);
        });
}


// Funkce pro otevření PDF v iframe
function openPdf(path) {
    const iframe = document.getElementById('pdfViewer');
    iframe.src = path;
}

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

        // cyklus: none → asc → desc → asc...
        if(order === 'none') order = 'asc';
        else if(order === 'asc') order = 'desc';
        else order = 'asc';

        // reset všech ostatních sloupců
        document.querySelectorAll('.sort-column').forEach(c => {
            if(c !== col) c.setAttribute('data-order','none');
        });

        // nastavení nového order
        col.setAttribute('data-order', order);

        // seřadit strom
        sortTree(type, order);
    });
});

function sortTree(type, order) {
    const container = document.getElementById('fileTree');
    const ul = container.querySelector('ul.pdf-tree');
    const items = Array.from(ul.children);

    const folders = items.filter(li => li.querySelector('strong'));
    const files = items.filter(li => li.querySelector('a'));

    files.sort((a, b) => {
        let cmp = 0;
        if(type === 'name') {
            cmp = a.querySelector('a').textContent.localeCompare(b.querySelector('a').textContent);
        } else if(type === 'date') {
            const dateA = new Date(a.querySelector('.file-date').textContent);
            const dateB = new Date(b.querySelector('.file-date').textContent);
            cmp = dateA - dateB;
        }
        return order === 'asc' ? cmp : -cmp;
    });

    ul.innerHTML = '';
    folders.forEach(folder => ul.appendChild(folder));
    files.forEach(file => ul.appendChild(file));
}





