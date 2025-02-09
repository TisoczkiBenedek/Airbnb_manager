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
            console.log("Válasz a szervertől:", adatok); // Hibakeresés: írd ki a választ

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
            alert('Hiba történt a megyék betöltése során: ' + error.message);
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
            
            // Olvasd el a választ szövegként
            const responseText = await response.text();
            
            // Próbáld JSON-ként értelmezni
            let result;
            try {
                result = JSON.parse(responseText); // JSON-ként értelmezzük
                console.log("Válasz a szervertől:", result);
            } catch (jsonError) {
                console.error('Hibás JSON válasz:', responseText);
                alert('Váratlan hiba történt! Kérjük, próbálja újra később.');
                return;
            }
            
            if (result.success) {
                alert(result.success);
                form.reset();
            } else {
                alert(result.error);
            }
        } catch (error) {
            console.error('Hiba a feltöltés során:', error);
            alert('Hiba történt a feltöltés során: ' + error.message);
        }
    });

    // Megyék betöltése az oldal betöltésekor
    megyekBetoltese();
});
