let adatok
var eszkozszam
var id_index = 1
async function eszkozLekeres() {
    try {
        let eredmeny = await fetch("../php/felhasznalok.php/eszkoz");
        if (eredmeny.ok) {
            adatok = await eredmeny.json()
            formFelt(false)
            eszkozszam = adatok.length
        }
    } catch (error) {
        console.log(error)
    }
}

if(id_index == eszkozszam ){
    id_index = 0
}
function formFelt(tovabbihoz) {
    //console.log("lefut: "+adatok.length)
    let eszkozok_db = adatok.length
    if (id_index <= eszkozok_db) {

        let form = document.getElementById("form")
        let select = document.createElement("select")
        let label1 = document.createElement("label")
        let label2 = document.createElement("label")
        let inputN = document.createElement("input")
        inputN.type = "number"
        inputN.id = "darab" + id_index
        inputN.classList.add("form-control", "w-75")
        label1.innerText = "Kérem válasszon eszközt!"
        label1.htmlFor = "eszk" + id_index
        label1.classList.add("form-label", "mt-2")
        label2.innerText = "Kérem válassza ki a rendelni kívánt mennyiséget!"
        label2.htmlFor = "darab" + id_index
        label2.classList.add("form-label", "mt-2")
        select.classList.add("form-select", "w-75")
        select.id = "eszk" + id_index
        id_index++
        for (let adat of adatok) {
            let opt = document.createElement("option")
            opt.value = adat["id"]
            opt.innerText = adat["nev"] + " " + adat["kiszereles"]
            select.appendChild(opt)
        }
        if (tovabbihoz == false) {
            let button = document.createElement("button")
            button.type = "button"
            button.classList.add("btn", "btn-success", "m-2")
            button.id = "gomb"
            button.innerText = "Igénylés leadása"
            button.setAttribute("onclick", "eszkozIgeny()")
            let button1 = document.createElement("button")
            button1.type = "button"
            button1.classList.add("btn", "btn-info", "m-2")
            button1.id = "tovabbi"
            button1.innerText = "További eszközök hozzáadása"
            button1.setAttribute("onclick", "tovabbiFelt()")
            form.appendChild(label1)
            form.appendChild(select)
            form.appendChild(label2)
            form.appendChild(inputN)
            form.appendChild(button1)
            form.appendChild(button)
        }
        else {
            let elotte = document.getElementById("tovabbi")
            form.insertBefore(label1, elotte)
            form.insertBefore(select, elotte)
            form.insertBefore(label2, elotte)
            form.insertBefore(inputN, elotte)
        }
    }
    if (id_index == eszkozok_db + 1) {
        let gomb = document.getElementById("tovabbi")
        gomb.disabled = true
        
    }



}
function tovabbiFelt() {
    //console.log("Belep")
    formFelt(true)
}
async function eszkozIgeny() {
    let akt = document.getElementsByClassName("form-select")
    let aktdb = document.querySelectorAll("input")
    let kuldendo = []
    for (let i = 0; i < akt.length; i++) {
        let id_darab = {
            "id": akt[i].value,
            "igenyelt": aktdb[i].value
        }
        kuldendo.push(id_darab)
    }
    let eredmeny = await fetch("../php/felhasznalok.php/eszkozIgeny", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(kuldendo)
    })
    let valasz = await eredmeny.json()
    let siker = false
    if (eredmeny.ok) {
        siker = true
    }
    valaszFelh(valasz, siker)
    igenyeltEszkozLeker()
    document.getElementById("form").innerText = ""
    id_index = 1
    eszkozLekeres()
}
function valaszFelh(valasz, siker) {
    let vhely = document.getElementById("vhely")
    let visszajelz = document.getElementById("visszajelz")
    let tbody = document.getElementById("tbody")
    let toast = document.getElementById("toast")
    visszajelz.innerText = ""
    toast.classList = ""
    tbody.innerText = ""
    if (siker == true) {
        toast.classList.add("bg-success-subtle", "toast")
        visszajelz.innerText = "Siker!"
        tbody.innerText = valasz["valasz"];
    }
    else {
        toast.classList.add("bg-danger-subtle", "toast")
        visszajelz.innerText = "Hiba történt!"
        tbody.innerText = valasz["valasz"];
    }
    const toastBootstrap = new bootstrap.Toast(toast)
    toastBootstrap.show()
    //setTimeout(() => vhely.hidden = true, 5005)
}
async function igenyeltEszkozLeker() {
    try {
        let id = 3
        let eredmeny = await fetch("../php/felhasznalok.php/igenyek?id=" + id)
        let adatok = await eredmeny.json()
        eszkozEddigIgenyelt(adatok)
        //console.log(adatok)
    } catch (error) {
        console.log(error)
    }
}
function eszkozEddigIgenyelt(adatok) {
    let div = document.getElementById("igenyek")
    div.innerText = ""
    if (adatok.valasz) {
        div.innerHTML = "<h3 class='text-danger mt-5'>" + adatok["valasz"] + "</h1>"
    }
    else {
        let keszlistazott = 0
        for (let adat of adatok) {
            let divigeny = document.createElement("div")
            divigeny.classList= ""
            let h4 = document.createElement("h4")
            h4.hidden = true
            if(adat["teljesitve"]== 1 && keszlistazott<=5){
                divigeny.classList.add("bg-success-subtle")
                h4.innerText = "TELJESÍTVE"
                h4.classList.add("text-center")
                h4.hidden = false
                keszlistazott++
            }
            divigeny.classList.add("col-12", "card", "mt-2", "mx-1")
            let cardb = document.createElement('div')
            cardb.classList.add('card-body')
            cardb.appendChild(h4)
            let h5 = document.createElement('h5')
            h5.classList.add("card-title")
            h5.innerText = adat['nev']
            cardb.appendChild(h5)
            let p = document.createElement('p')
            p.classList.add('card-text')
            p.innerHTML = "Igényelt darabszám: " + adat['igenyeltDarab']
            cardb.appendChild(p)
            let p1 = document.createElement('p')
            p1.classList.add('card-text')
            p1.innerHTML = "Kiszerelés: " + adat['kiszereles']
            cardb.appendChild(p1)
            divigeny.appendChild(cardb)
            if(keszlistazott>= 5 && adat["teljesitve"]==1){
                divigeny.hidden = true
            }
            div.appendChild(divigeny)
        }
    }
}

// Profilnév lekérése és megjelenítése
function loadProfilNev() {
    fetch('../php/naptarTakarito.php?action=getProfilAdat')
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
            document.getElementById('profilNev').innerText = "Hiba a profilnév betöltésekor";
        });
}

//kijelentkezés
async function kijelentkezes() {
    try {
        const eredmeny = await fetch('../php/kijelentkezes.php', {
            method: 'POST',
            credentials: 'include'
        });

        if (eredmeny.ok) {
            const adat = await eredmeny.json();

            if (adat.success) {
                window.location.href = '../../html/bejelentkezes.html';
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

window.addEventListener('load', loadProfilNev)
window.addEventListener("load", eszkozLekeres)
window.addEventListener("load", igenyeltEszkozLeker)
/*try {
    //console.log("Event")
    document.getElementById("tovabbi").addEventListener("click", tovabbiFelt)
    document.getElementById("gomb").addEventListener("click", eszkozIgeny)
} catch (error) {
    setTimeout(() => document.getElementById("tovabbi").addEventListener("click", tovabbiFelt), 200)
    setTimeout(() => document.getElementById("gomb").addEventListener("click", eszkozIgeny), 200)
}*/

