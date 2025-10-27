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


