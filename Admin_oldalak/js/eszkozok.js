// Profilnév lekérése és megjelenítése
function loadProfilNev() {
    fetch('../php/lakasok.php?action=getProfilNev')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP hiba! Státusz: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.profilNev) {
                document.getElementById('profilNev').innerText = data.profilNev;
            }
        })
        .catch(error => {
            console.error('Hiba a profilnév betöltésekor:', error);
            document.getElementById('profilNev').innerText = "Nincsen bejelentkezve";
        });
}

// Oldal betöltésekor futtatjuk
document.addEventListener('DOMContentLoaded', loadProfilNev)

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