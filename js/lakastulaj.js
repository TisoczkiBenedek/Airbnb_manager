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
        img.src = adat.kepek && adat.kepek.length > 0 ? adat.kepek[0] : '../images/default.jpg';
        img.classList.add("card-img-top");
        img.onerror = function() {
            this.src = '../images/default.jpg';
        };
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
        button.setAttribute('onclick', `naptarOldalra(${adat.id})`); // Átadjuk a lakasId-t
        cardb.appendChild(button);

        // Módosítás gomb
        let button1 = document.createElement('input');
        button1.type = "button";
        button1.classList.add("btn", "btn-info", "mt-2", "ms-2");
        button1.value = "Módosítás";
        button1.setAttribute("onclick", "modositasModal(" + adat.id + ")");
        button1.setAttribute("data-bs-toggle", "modal");
        button1.setAttribute("data-bs-target", "#modal_modosit");
        cardb.appendChild(button1);

        //törlés gomb
        let button2 = document.createElement('input');
        button2.type = "button";
        button2.classList.add("btn", "btn-danger", "mt-2", "ms-2");
        button2.value = "Törlés"
        button2.setAttribute('onclick', `lakasTorles(${adat.id})`); // Átadjuk a lakasId-t
        cardb.appendChild(button2);

        card.appendChild(cardb);
        div.appendChild(card);
        valasz.appendChild(div);
    }
}

function naptarOldalra(lakasId) {
    console.log("Átadott lakasId:", lakasId); // Ellenőrzés
    window.location.href = `../html/naptar.html?lakas_id=${lakasId}`;
}

function modositasModal(id){
    console.log(id)
    document.getElementById('mentes').disabled = false
    document.getElementById('torles').disabled = false
    let modalform = document.getElementById('modal_form')
    modalform.innerText = ""
    let p = document.getElementsByClassName('modal_valasz')
    p[0].innerText = ""
    p[0].style.border = "none"
    p[0].hidden = true
    //let modalcim = document.getElementById('modal_cim')
    //modalcim.innerText = "Lakás módosítása "
    let span = document.getElementById('modal_lakas_id')
    span.innerText = ""
    span.innerText = id
    let label = document.createElement('label')
    label.classList.add('form-label')
    label.innerText = "Lakástulajdonos e-mail címe"
    modalform.appendChild(label)
    let cim = document.createElement("input")
    cim.id = "email"
    cim.type = "text"
    cim.value = document.getElementsByClassName(id)[0].innerText
    cim.classList.add("form-control")
    modalform.appendChild(cim)
}
async function modositas(){
    let email = document.getElementById('email')
    if(email.value == ""){
        alert("Kérem jelentkezzen be!")
        return
    }
    if(!email.value.includes("@")){
        alert("Hibásan megadott e-mail cím!")
        return
    }
    else{
        try {
            let kuldendo = {
                "id": document.getElementById('modal_lakas_id').innerText,
                "email": email.value
            }
            let eredmeny = await fetch('../php/lakasok.php/modositas', {
                method : "POST",
                headers : {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(kuldendo)
            })
            if(eredmeny.ok){
                let adatok = await eredmeny.json()
                console.log(adatok)
                valasz(adatok, false)

            }
            else if(eredmeny.status == 400){
                let valaszer = await eredmeny.json()
                valasz(valaszer, false)
            }
        } catch (error) {
            console.log(error)
        }
    }
}

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

//A kép modal ablak bezárása a képen kívülre kattintva
window.onclick = function(event) {
    const modal = document.getElementById("kepModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

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
document.addEventListener('DOMContentLoaded', loadProfilNev);

async function kijelentkezes(){
    try {
        const eredmeny = await fetch('../php/kijelentkezes.php', {
            method: 'POST',
            credentials: 'include'
        }); 

        if(eredmeny.ok){
            const adat = await eredmeny.json();

            if(adat.success) {
                window.location.href = '../html/bejelentkezes.html';
            }else{
                console.error('Hiba a kijelentkezés során:', adat.message);
            }
        }else{
            throw new Error(`HTTP hiba! Státusz: ${eredmeny.status}`);
        }
    } catch (error) {
        console.error('Hiba a kijelentkezés során: ', error)
    }
    
}