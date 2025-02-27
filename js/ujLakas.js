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
        }
    }

    // Űrlap beküldése
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(form);
    
        try {
            const response = await fetch('../php/ujLakas.php?feltoltes', {
                method: 'POST',
                body: formData
            });
    
            if (!response.ok) {
                throw new Error(`HTTP hiba! Státusz: ${response.status}`);
            }
    
            const responseText = await response.text();
            console.log("Szerver válasza:", responseText); // Hibakereséshez
    
            let result;
            try {
                result = JSON.parse(responseText); // Próbáljuk értelmezni a JSON-t
            } catch (jsonError) {
                console.error('Hibás JSON válasz:', responseText);
                alert('A szerver hibás választ adott. Kérjük, próbálja újra később.');
                return;
            }
    
            if (result.error) {
                document.getElementById('toast-body').textContent = result.error;
            } else if (result.success) {
                document.getElementById('toast-body').textContent = result.success;
                form.style.display = 'none'; // Űrlap eltűntetése
            
                // Oldal frissítése 2 másodperc múlva
                setTimeout(() => {
                    location.reload(); // Az oldal teljes frissítése
                }, 1000); // 2000 ms = 2 másodperc
            } else {
                document.getElementById('toast-body').textContent = "Ismeretlen hiba történt.";
            }
    
            const toastLiveExample = document.getElementById('liveToast');
            const toast = new bootstrap.Toast(toastLiveExample);
            toast.show();
    
        } catch (error) {
            console.error('Hiba a feltöltés során:', error);
            alert('Hiba történt a feltöltés során: ' + error.message);
        }
    });

    // Megyék betöltése az oldal betöltésekor
    megyekBetoltese();
});
