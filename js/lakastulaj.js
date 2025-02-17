// document.addEventListener('DOMContentLoaded', function () {
//     console.log('DOM betöltődött');
//     adatokLekerese(); // Lakások betöltése
// });

// async function adatokLekerese() {
//     try {
//         console.log("Adatok lekérése elkezdődött");
//         const response = await fetch('../php/lakasok.php?action=lekeres');
        
//         if (!response.ok) {
//             throw new Error(`HTTP hiba! Státusz: ${response.status}`);
//         }

//         const adatok = await response.json();
//         console.log("Adatok érkeztek:", adatok);
//         kiiras(adatok); // Adatok megjelenítése
//     } catch (error) {
//         console.error("Hiba:", error);
//         alert("Adatok betöltése sikertelen!");
//     }
// }

// function kiiras(adatok) {
//     let valasz = document.getElementById('valasz');
//     valasz.innerText = "";
//     for (let adat of adatok) {
//         let div = document.createElement('div');
//         div.classList.add("col-sm-12", "col-md-4", "col-lg-3", "mt-3", adat['emailcim']);
//         let card = document.createElement('div');
//         card.classList.add("card");

//         // Kép betöltése
//         let img = document.createElement('img');
//         img.src = adat.kepek && adat.kepek.length > 0 ? adat.kepek[0] : '../images/default.jpg';
//         img.classList.add("card-img-top");
//         img.onerror = function() {
//             this.src = '../images/default.jpg';
//         };
//         card.appendChild(img);

//         let cardb = document.createElement('div');
//         cardb.classList.add('card-body');

//         let h5 = document.createElement('h5');
//         h5.classList.add("card-title");
//         h5.innerText = adat.nev;
//         cardb.appendChild(h5);

//         let p = document.createElement('p');
//         p.classList.add('card-text');
//         p.innerHTML = adat.cim + "<br>Terület: " + adat.terulet + " m²<br>Medence: " + (adat.medence && adat.medence == 1 ? 'Van' : 'Nincs') + "<br>Szauna: " + (adat.szauna && adat.szauna == 1 ? 'Van' : 'Nincs') + "<br>Belépési adatok: <br>" + adat.belepesi_adatok;
//         cardb.appendChild(p);

//         // Módosítás gomb
//         let button1 = document.createElement('input');
//         button1.type = "button";
//         button1.classList.add("btn", "btn-info", "mt-2");
//         button1.value = "Módosítás";
//         button1.setAttribute("onclick", "modositasModal(" + adat.id + ")");
//         button1.setAttribute("data-bs-toggle", "modal");
//         button1.setAttribute("data-bs-target", "#modal_modosit");
//         cardb.appendChild(button1);

//         // Naptár gomb
//         let button = document.createElement('input');
//         button.type = "button";
//         button.classList.add("btn", "btn-success", "mt-2", "ms-2");
//         button.value = "Naptár";
//         button.setAttribute('onclick', `naptarOldalra(${adat.id})`); // Átadjuk a lakasId-t
//         cardb.appendChild(button);

//         card.appendChild(cardb);
//         div.appendChild(card);
//         valasz.appendChild(div);
//     }
// }

// function naptarOldalra(lakasId) {
//     window.location.href = `../html/naptar.html?lakas_id=${lakasId}`;
// }

// function modositasModal(id){
//     console.log(id)
//     document.getElementById('mentes').disabled = false
//     document.getElementById('torles').disabled = false
//     let modalform = document.getElementById('modal_form')
//     modalform.innerText = ""
//     let p = document.getElementsByClassName('modal_valasz')
//     p[0].innerText = ""
//     p[0].style.border = "none"
//     p[0].hidden = true
//     //let modalcim = document.getElementById('modal_cim')
//     //modalcim.innerText = "Lakás módosítása "
//     let span = document.getElementById('modal_lakas_id')
//     span.innerText = ""
//     span.innerText = id
//     let label = document.createElement('label')
//     label.classList.add('form-label')
//     label.innerText = "Lakástulajdonos e-mail címe"
//     modalform.appendChild(label)
//     let cim = document.createElement("input")
//     cim.id = "email"
//     cim.type = "text"
//     cim.value = document.getElementsByClassName(id)[0].innerText
//     cim.classList.add("form-control")
//     modalform.appendChild(cim)
// }
// async function modositas(){
//     let email = document.getElementById('email')
//     if(email.value == ""){
//         alert("Kérem töltse ki az e-mail címet!")
//         return
//     }
//     if(!email.value.includes("@")){
//         alert("Hibásan megadott e-mail cím!")
//         return
//     }
//     else{
//         try {
//             let kuldendo = {
//                 "id": document.getElementById('modal_lakas_id').innerText,
//                 "email": email.value
//             }
//             let eredmeny = await fetch('../php/lakasok.php/modositas', {
//                 method : "POST", 
//                 headers : {
//                     "Content-Type": "application/json"
//                 },
//                 body: JSON.stringify(kuldendo)
//             })
//             if(eredmeny.ok){
//                 let adatok = await eredmeny.json()
//                 console.log(adatok)
//                 valasz(adatok, false)
                
//             }
//             else if(eredmeny.status == 400){
//                 let valaszer = await eredmeny.json()
//                 valasz(valaszer, false)
//             }
//         } catch (error) {
//             console.log(error)
//         }
//     }
// }

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM betöltődött');
    adatokLekerese(); // Lakások betöltése
});

async function adatokLekerese() {
    try {
        console.log("Adatok lekérése elkezdődött");
        const response = await fetch('../php/lakasok.php?action=lekeres');

        if (!response.ok) {
            throw new Error(`HTTP hiba! Státusz: ${response.status}`);
        }

        const adatok = await response.json();
        console.log("Adatok érkeztek:", adatok);
        kiiras(adatok); // Adatok megjelenítése
    } catch (error) {
        console.error("Hiba:", error);
        alert("Adatok betöltése sikertelen!");
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

        // Módosítás gomb
        let button1 = document.createElement('input');
        button1.type = "button";
        button1.classList.add("btn", "btn-info", "mt-2");
        button1.value = "Módosítás";
        button1.setAttribute("onclick", "modositasModal(" + adat.id + ")");
        button1.setAttribute("data-bs-toggle", "modal");
        button1.setAttribute("data-bs-target", "#modal_modosit");
        cardb.appendChild(button1);

        // Naptár gomb
        let button = document.createElement('input');
        button.type = "button";
        button.classList.add("btn", "btn-success", "mt-2", "ms-2");
        button.value = "Naptár";
        button.setAttribute('onclick', `naptarOldalra(${adat.id})`); // Átadjuk a lakasId-t
        cardb.appendChild(button);

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
        alert("Kérem töltse ki az e-mail címet!")
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