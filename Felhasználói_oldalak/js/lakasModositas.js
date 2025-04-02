async function modositasModal(id) {
    try {
        const response = await fetch(`../php/lakasok.php?action=getLakas&id=${id}`);
        const lakas = await response.json();
        
        // Űrlap elemek feltöltése
        const form = document.getElementById('modositForm');
        form.dataset.lakasId = id;
        
        form.querySelector('#lakasNev').value = lakas.nev;
        form.querySelector('#lakcim').value = lakas.cim;
        form.querySelector('#terulet').value = lakas.terulet;
        form.querySelector('#medence').checked = lakas.medence === 1;
        form.querySelector('#szauna').checked = lakas.szauna === 1;
        form.querySelector('#lakasAdatok').value = lakas.belepesi_adatok;
        
        // Megye dropdown feltöltése
        const megyeSelect = form.querySelector('#megye');
        await megyeDropdown(megyeSelect, lakas.megye_id);

    } catch (error) {
        console.error('Hiba:', error);
        showToast('Hiba az adatok betöltésekor', 'danger');
    }
}

async function megyeDropdown(selectElement, selectedId) {
    try {
        const response = await fetch('../php/megyeLista.php');
        const megyek = await response.json();
        
        selectElement.innerHTML = '<option value="">Válasszon megyét</option>';
        megyek.forEach(megye => {
            const option = document.createElement('option');
            option.value = megye.id;
            option.textContent = megye.megyeNev;
            option.selected = megye.id === selectedId;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error('Hiba a megyék betöltésekor:', error);
    }
}

// Űrlap beküldése
document.getElementById('modositForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('id', this.dataset.lakasId);

    try {
        const response = await fetch('../php/lakasok.php?action=modositas', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Hiba történt a módosítás során');
        }

        if (result.success) {
            showToast(result.message, 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('modal_modosit'));
            modal.hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(result.message, 'danger');
        }
    } catch (error) {
        console.error('Hiba:', error);
        showToast(error.message, 'danger');
    }
});

// Toast üzenetek
function showToast(message, type = 'info') {
    const toast = document.getElementById('liveToast');
    const toastBody = toast.querySelector('.toast-body');
    toastBody.textContent = message;

    // Eltávolítjuk az összes korábbi stílusosztályt
    toast.classList.remove('bg-info', 'bg-success', 'bg-danger', 'bg-warning');
    
    // Hozzáadjuk az új stílusosztályt
    toast.classList.add(`bg-${type}`);

    const toastInstance = new bootstrap.Toast(toast);
    toastInstance.show();
    
    // 5 másodperc után eltűnik
    setTimeout(() => {
        toastInstance.hide();
    }, 5000);
}