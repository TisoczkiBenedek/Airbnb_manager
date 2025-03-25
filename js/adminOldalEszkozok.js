document.addEventListener("DOMContentLoaded", function() {
    // Profilnév betöltése
    const profilNevElement = document.getElementById("profilNev");
    if (profilNevElement) {
        // Itt lehet session-ből vagy localStorage-ből betölteni a nevet
        const userName = "Admin"; 
        profilNevElement.textContent = userName;
    }

    // Kijelentkezés funkció
    window.kijelentkezes = function() {
        fetch('../php/kijelentkezes.php')
            .then(response => {
                if(response.ok) {
                    window.location.href = '../html/bejelentkezes.html';
                }
            });
    };
});

// Összes kijelölése
document.getElementById('osszes_kivalasztasa').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="torlendo_eszkozok[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Gyors frissítés egy sorra
function gyorsFrissites(id, gomb) {
    const sor = gomb.closest('tr');
    const mennyisegInput = sor.querySelector('input[name="mennyiseg[' + id + ']"]');
    const mennyiseg = mennyisegInput.value;
    
    // AJAX kérés a frissítéshez
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'eszkozok.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            alert('Sikeres frissítés!');
        } else {
            alert('Hiba történt a frissítés során!');
        }
    };
    xhr.send('mennyiseg[' + id + ']=' + mennyiseg + '&frissites=1');
}