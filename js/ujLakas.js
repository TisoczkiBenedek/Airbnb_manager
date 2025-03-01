document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('lakasForm');

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
    if (form) {
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Hiányos adatok ellenőrzése
            const lakasNev = document.getElementById('lakasNev').value.trim();
            const lakcim = document.getElementById('lakcim').value.trim();
            const terulet = document.getElementById('terulet').value.trim();
            const megye = document.getElementById('megye').value.trim();
            const lakasAdatok = document.getElementById('lakasAdatok').value.trim();

            if (!lakasNev || !lakcim || !terulet || !megye || !lakasAdatok) {
                showToast("Kérem töltse ki az összes kötelező mezőt!", 'danger');
                return;
            }

            // Adatok elküldése
            const formData = new FormData(form);
            try {
                const response = await fetch('../php/ujLakas.php?feltoltes', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) throw new Error(`HTTP hiba! Státusz: ${response.status}`);
                const result = await response.json();

                if (result.error) {
                    showToast(result.error, 'danger');
                } else if (result.success) {
                    showToast(result.success, 'success');
                    form.reset(); // Űrlap resetelése
                    setTimeout(() => location.reload(), 1000); // Oldal frissítése
                }
            } catch (error) {
                console.error('Hiba a feltöltés során:', error);
                showToast('Hiba történt a feltöltés során.', 'danger');
            }
        });
    }

    // Megyék betöltése
    megyekBetoltese();
});

// Toaster üzenetek megjelenítése
function showToast(message, type = 'info') {
    const toast = document.getElementById('liveToast');
    const toastBody = toast.querySelector('.toast-body');
    toastBody.textContent = message;

    // Toaster stílus beállítása
    toast.classList.remove('bg-info', 'bg-success', 'bg-danger');
    toast.classList.add(`bg-${type}`);

    // Toaster megjelenítése
    const toastInstance = new bootstrap.Toast(toast);
    toastInstance.show();

    // Toaster eltüntetése 5 másodperc múlva
    setTimeout(() => {
        toastInstance.hide();
    }, 5000);
}