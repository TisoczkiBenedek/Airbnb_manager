document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('lakasForm');

    if (!form) {
        console.error("Az űrlap (#lakasForm) nem található az oldalon.");
        return;
    }
    
    // Megyék betöltése
    async function megyekBetoltese() {
        try {
            const response = await fetch('../php/ujLakas.php?megyek');
            if (!response.ok) {
                throw new Error(`HTTP hiba! Státusz: ${response.status}`);
            }

            const adatok = await response.json();
            console.log("Válasz a szervertől:", adatok);

            const select = document.getElementById('megye');
            select.innerHTML = '<option value="">Válasszon megyét</option>';

            adatok.forEach(megye => {
                const opt = document.createElement('option');
                opt.value = megye.id;
                opt.textContent = megye.megyeNev;
                select.appendChild(opt);
            });
        } catch (error) {
            console.error('Hiba a megyék betöltése során:', error);
            showToast('Hiba történt a megyék betöltése során.', 'danger');
        }
    }

    // Űrlap beküldése
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Kötelező mezők ellenőrzése
        const lakasNev = document.getElementById('lakasNev').value.trim();
        const lakcim = document.getElementById('lakcim').value.trim();
        const terulet = document.getElementById('terulet').value.trim();
        const megye = document.getElementById('megye').value;
        const lakasAdatok = document.getElementById('lakasAdatok').value.trim();
    
        function lakcimEll(lakcim) {
            const regex = /^\d{4} .+, .+ \d+/;
            return regex.test(lakcim.trim());
        }
    
        try {
            // 1. Kötelező mezők ellenőrzése
            if (!lakasNev || !terulet || !megye || !lakasAdatok) {
                throw new Error("Kérem töltse ki az összes kötelező mezőt!");
            }
    
            // 2. Cím formátum ellenőrzése
            if (!lakcimEll(lakcim)) {
                throw new Error("Érvénytelen címformátum! Példa: 1013 Budapest, Kossuth utca 12, vagy \n 1013 Budapest, Kossuth Lajos utca 12/A. emelet 3");
            }
    
            // 3. FormData létrehozása
            const formData = new FormData(form);
    
            // 4. Adatok elküldése
            const response = await fetch('../php/ujLakas.php?feltoltes', {
                method: 'POST',
                body: formData
            });
    
            const text = await response.text();
            console.log("Szerver válasza:", text);
    
            try {
                const result = JSON.parse(text);
    
                if (result.error) {
                    showToast(result.error, 'danger');
                } else if (result.success) {
                    showToast(result.success, 'success');
                    form.reset();
                    setTimeout(() => location.reload(), 500);
                }
            } catch (jsonError) {
                console.error('Hiba a JSON feldolgozásánál:', jsonError);
                console.error('Kapott válasz:', text);
                showToast('Hiba történt a szerver válaszának feldolgozása során.', 'danger');
            }
            
        } catch (error) {
            console.error('Hiba:', error);
            showToast(error.message, 'danger');
        }
    });
        /*
        // Adatok elküldése
        const formData = new FormData(form);
        try {
            const response = await fetch('../php/ujLakas.php?feltoltes', {
                method: 'POST',
                body: formData
            });

            const text = await response.text();
            console.log("Szerver válasza:", text);

            try {
                const result = JSON.parse(text);

                if (result.error) {
                    showToast(result.error, 'danger');
                } else if (result.success) {
                    showToast(result.success, 'success');
                    form.reset();
                    setTimeout(() => location.reload(), 500);
                }
            } catch (jsonError) {
                console.error('Hiba a JSON feldolgozásánál:', jsonError);
                console.error('Kapott válasz:', text);
                showToast('Hiba történt a szerver válaszának feldolgozása során.', 'danger');
            }
        } catch (error) {
            console.error('Hiba a feltöltés során:', error);
            showToast('Hiba történt a feltöltés során.', 'danger');
        } 
                   
    });
*/

    // Megyék betöltése
    megyekBetoltese();
});

// Toaster üzenetek megjelenítése
function showToast(message, type = 'info') {
    const toast = document.getElementById('liveToast');
    const toastBody = toast.querySelector('.toast-body');
    toastBody.textContent = message;

    toast.classList.remove('bg-info', 'bg-success', 'bg-danger');
    toast.classList.add(`bg-${type}`);

    const toastInstance = new bootstrap.Toast(toast);
    toastInstance.show();

    setTimeout(() => {
        toastInstance.hide();
    }, 5000);
}
