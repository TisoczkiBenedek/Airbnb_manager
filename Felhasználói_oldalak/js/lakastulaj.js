document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM betöltődött');
    adatokLekerese(); // Lakások betöltése
});

async function adatokLekerese() {
    try {
        const eredmeny = await fetch('../php/lakasok.php?action=lekeres', {
            method: 'GET',
            credentials: 'include' // Küldjük el a sütiket
        });

        if (!eredmeny.ok) {
            throw new Error(`HTTP hiba! Státusz: ${eredmeny.status}`);
        }

        const adatok = await eredmeny.json();
        kiiras(adatok);
    } catch (error) {
        console.error("Hiba:", error);
        showToast("Adatok betöltése sikertelen!", 'danger');
    }
}

function kiiras(adatok) {
    let valasz = document.getElementById('valasz');
    valasz.innerText = "";
    for (let adat of adatok) {
        let div = document.createElement('div');
        div.classList.add("col-sm-12", "col-md-4", "col-lg-3", "mt-3", adat['emailcim']);
        let card = document.createElement('div');
        card.classList.add("card");

        // Kép betöltése
        let img = document.createElement('img');
        img.src = adat.kepek;
        img.classList.add("card-img-top");
        img.onclick = function() {
            nagyKepMegjelenites(this);
        };
        card.appendChild(img);

        let cardb = document.createElement('div');
        cardb.classList.add('card-body');

        let h5 = document.createElement('h5');
        h5.classList.add("card-title");
        h5.innerText = adat.nev;
        cardb.appendChild(h5);

        let p = document.createElement('p');
        p.classList.add('card-text');
        p.innerHTML = adat.cim + "<br>Terület: " + adat.terulet + " m²<br>Medence: " + (adat.medence && adat.medence == 1 ? 'Van' : 'Nincs') + "<br>Szauna: " + (adat.szauna && adat.szauna == 1 ? 'Van' : 'Nincs') + "<br>Belépési adatok: <br>" + adat.belepesi_adatok;
        cardb.appendChild(p);

        // Naptár gomb
        let button = document.createElement('input');
        button.type = "button";
        button.classList.add("btn", "btn-success", "mt-2", "ms-2");
        button.value = "Naptár";
        button.setAttribute('onclick', `naptarOldalra(${adat.id}, ${adat.megye_id})`);
        cardb.appendChild(button);

        // Módosítás gomb
        let button1 = document.createElement('input');
        button1.type = "button";
        button1.classList.add("btn", "btn-warning", "mt-2", "ms-2");
        button1.value = "Módosítás";
        button1.setAttribute("onclick", "modositasModal(" + adat.id + ")");
        button1.setAttribute("data-bs-toggle", "modal");
        button1.setAttribute("data-bs-target", "#modal_modosit");
        cardb.appendChild(button1);

        // Törlés gomb
        let button2 = document.createElement('input');
        button2.type = "button";
        button2.classList.add("btn", "btn-danger", "mt-2", "ms-2");
        button2.value = "Törlés"
        button2.addEventListener("click", () => {
            selectedLakasId = adat.id;
            document.getElementById("torlesModal").style.display = "block";
        });
        cardb.appendChild(button2);

        card.appendChild(cardb);
        div.appendChild(card);
        valasz.appendChild(div);
    }
}

function naptarOldalra(lakasId, megyeId) {
    console.log("Átadott lakasId:", lakasId);
    console.log("Átadott megyeId:", megyeId);
    window.location.href = `../html/naptar.html?lakas_id=${lakasId}&megye_id=${megyeId}`;
}

async function modositasModal(id) {
    console.log(id);
    document.getElementById('lakasId').value = id;

    try {
        const lakasResponse = await fetch(`../php/lakasok.php?action=getLakas&id=${id}`);
        const lakasData = await lakasResponse.json();
        
        if (lakasData) {
            document.getElementById('lakasNev').value = lakasData.nev;
            document.getElementById('lakcim').value = lakasData.cim;
            document.getElementById('terulet').value = lakasData.terulet;
            document.getElementById('medence').checked = lakasData.medence === 1;
            document.getElementById('szauna').checked = lakasData.szauna === 1;
            document.getElementById('megye').value = lakasData.megye_id;
            document.getElementById('lakasAdatok').value = lakasData.belepesi_adatok;
        }
    } catch (error) {
        console.error('Hiba az adatok betöltésekor:', error);
    }
}

document.getElementById('modositForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const lakasId = formData.get('id');

    try {
        // Naptár fájl kezelése
        const icsFile = formData.get('naptarFeltoltes');
        if (icsFile && icsFile.size > 0) {
            const icsContent = await icsFile.text();
            formData.set('naptar_content', icsContent);
        }

        // Kép fájl kezelése
        const kepFile = formData.get('kepFeltoltes');
        if (kepFile && kepFile.size > 0) {
            formData.set('kepFeltoltes', kepFile);
        }

        // Küldés a szervernek
        const response = await fetch(`../php/lakasok.php?action=modositas`, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            showToast("Sikeres módosítás!", 'success');
            adatokLekerese();
            $('#modal_modosit').modal('hide');
        } else {
            showToast(result.error || "Hiba történt", 'danger');
        }
    } catch (error) {
        console.error('Hiba:', error);
        showToast("Szerverhiba történt", 'danger');
    }
});

// A kép modal ablak megjelenítése
function nagyKepMegjelenites(kep) {
    const modal = document.getElementById("kepModal");
    const modalKep = document.getElementById("modalKep");
    modal.style.display = "block";
    modalKep.src = kep.src;
}

// A kép modal ablak bezárása
function modalBezaras() {
    const modal = document.getElementById("kepModal");
    modal.style.display = "none";
}

// A kép modal ablak bezárása a képen kívülre kattintva
window.onclick = function(event) {
    const modal = document.getElementById("kepModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

// Profilnév lekérése és megjelenítése
async function loadProfilNev() {
    try {
        const response = await fetch('../php/lakasok.php?action=getProfilNev');
        
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
document.addEventListener('DOMContentLoaded', loadProfilNev);

async function kijelentkezes() {
    try {
        const eredmeny = await fetch('../php/kijelentkezes.php', {
            method: 'POST',
            credentials: 'include'
        });

        if (eredmeny.ok) {
            const adat = await eredmeny.json();

            if (adat.success) {
                window.location.href = '../html/bejelentkezes.html';
            } else {
                console.error('Hiba a kijelentkezés során:', adat.message);
            }
        } else {
            throw new Error(`HTTP hiba! Státusz: ${eredmeny.status}`);
        }
    } catch (error) {
        console.error('Hiba a kijelentkezés során: ', error);
    }
}

// Toast üzenet megjelenítése
function showToast(message, type) {
    const toastBody = document.getElementById('toast-body');
    toastBody.innerText = message;
    const toast = new bootstrap.Toast(document.getElementById('liveToast'));
    toast.show();
}
