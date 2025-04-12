// Profilnév lekérése és megjelenítése (async/await verzió)
async function loadProfilNev() {
    try {
        const response = await fetch('../php/lakasok.php?action=getProfilAdat', {
            credentials: 'include' // Küldjük el a sütiket
        });
        
        if (!response.ok) {
            throw new Error(`HTTP hiba! Státusz: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.profilNev) {
            document.getElementById('profilNev').innerText = data.profilNev;
        }
    } catch (error) {
        console.error('Hiba a profilnév betöltésekor:', error);
        document.getElementById('profilNev').innerText = "Nincsen bejelentkezve";
    }
}

// Oldal betöltésekor futtatjuk
document.addEventListener('DOMContentLoaded', () => {
    loadProfilNev();
    eszkozKezeles();
    eszkozKereses();
});

function eszkozKereses() {
    const nevSzuro = document.getElementById("nevSzuro")
    const szuresBtn = document.getElementById("szuresGomb")
    const szuresReset = document.getElementById("szuresReset")

    if (nevSzuro && szuresGomb && szuresReset) {
        // Keresés gomb eseménykezelő
        szuresBtn.addEventListener('click', function() {
            tablaSzures();
        });
        
        // Enter billentyű eseménykezelő
        nevSzuro.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                tablaSzures();
            }
        });
        
        // Szűrő reset gomb
        szuresReset.addEventListener('click', function() {
            nevSzuro.value = '';
            tablaSzures();
        });
    }
}

// Tábla szűrése
function tablaSzures() {
    const keresett = document.getElementById('nevSzuro').value.toLowerCase();
    const sorok = document.querySelectorAll('tbody tr');
    
    sorok.forEach(sor => {
        const nev = sor.cells[1].textContent.toLowerCase();
        const kiszereles = sor.cells[2].textContent.toLowerCase();
        const keszleten = sor.cells[3].querySelector('input').value.toLowerCase();
        
        if (nev.includes(keresett) || 
            kiszereles.includes(keresett) || 
            keszleten.includes(keresett)) {
            sor.style.display = '';
        } else {
            sor.style.display = 'none';
        }
    });
}


// Eszközkezelés inicializálása
function eszkozKezeles() {
    // Összes kijelölése
    const osszesKivalasztasa = document.getElementById('osszes_kivalasztasa');
    if (osszesKivalasztasa) {
        osszesKivalasztasa.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="torlendo_eszkozok[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Gyors frissítés gombok eseménykezelői
    document.querySelectorAll('.gyors-frissites').forEach(gomb => {
        gomb.addEventListener('click', function() {
            const id = this.dataset.id;
            gyorsFrissites(id, this);
        });
    });
}

// Gyors frissítés egy sorra (async/await verzió)
async function gyorsFrissites(id, gomb) {
    const sor = gomb.closest('tr');
    const mennyisegInput = sor.querySelector(`input[name="mennyiseg[${id}]"]`);
    const mennyiseg = mennyisegInput.value;
    
    // Validáció
    if (isNaN(mennyiseg) || mennyiseg < 0) {
        alert('Kérjük érvényes mennyiséget adjon meg!');
        return;
    }

    try {
        const response = await fetch('eszkozok.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `mennyiseg[${id}]=${mennyiseg}&frissites=1`,
            credentials: 'include'
        });
        
        if (response.ok) {
            const data = await response.text();
            if (data.includes('Sikeres')) {
                showToast('Sikeres frissítés!', 'success');
            } else {
                throw new Error(data);
            }
        } else {
            throw new Error(`HTTP hiba! Státusz: ${response.status}`);
        }
    } catch (error) {
        console.error('Hiba a frissítés során:', error);
        showToast('Hiba történt a frissítés során!', 'danger');
    }
}

// Toast üzenet megjelenítése
function showToast(message, type = 'success') {
    const toastElement = document.getElementById('liveToast');
    const toastBody = document.getElementById('toast-body');
    
    if (!toastElement || !toastBody) {
        console.warn('Toast elemek nem találhatók');
        alert(message); // Fallback ha nincs toast rendszer
        return;
    }
    
    toastBody.textContent = message;
    toastElement.classList.remove('bg-success', 'bg-danger', 'bg-warning');
    toastElement.classList.add(`bg-${type}`);
    
    // Bootstrap Toast példányosítása és megjelenítése
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}