async function adatokLekerese() {
    let adatkuld = {
        "id": 3
    }
    try {
        let eredmeny = await fetch('../php/felhasznalok.php/lekeres', {
            method : "POST", 
            headers : {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(adatkuld)
        })
        if(eredmeny.ok){
            let adatok = await eredmeny.json();
            console.log(adatok)
            kiiras(adatok)
        }
        else{
            let valasz = await eredmeny.json()
            kijelzes(valasz)
        }
    } catch (error) {
        console.log(error)
    }
}
let megyek = []
async function megyelekeres(){
    try {
        let eredmeny = await fetch('../php/felhasznalok.php/megyek')
        if(eredmeny.ok){
            let adatok = await eredmeny.json()
            megyek = adatok
        }
        else{
            throw new error
        }
    } catch (error) {
        console.log(error)
    }
}
function kijelzes(valasz){
    console.log(valasz)
    if(valasz["valasz"]== "Nincsenek találatok"){
        
        let div = document.getElementById('valasz')
        div.innerText = ""
        div.innerHTML = "<h1>Nincsenek a mai napra takarítások!</h1>"
        div.classList.add("mx-auto", "text-center", "mt-5")
    }
}
function kiiras(adatok){
    let valasz = document.getElementById('valasz')
    valasz.innerText = ""
    valasz.classList = ""
    valasz.classList.add("row")
    for (let adat of adatok) {
        let div = document.createElement('div')
        div.classList.add("col-sm-12", "col-md-4", "col-lg-3", "mt-3", "mx-1")
        let card = document.createElement('div')
        card.classList.add("card")
        let cardb = document.createElement('div')
        cardb.classList.add('card-body')
        let h5 = document.createElement('h5')
        h5.classList.add("card-title")
        h5.innerText = adat['nev']
        cardb.appendChild(h5)
        let p = document.createElement('p')
        p.classList.add('card-text')
        p.innerHTML ="Cím: "+ adat['cim']
        cardb.appendChild(p)
        
        let p1 = document.createElement('p')
        p1.classList.add('card-text')
        p1.innerHTML ="Belépsi adatok: "+ adat['belepesi_adatok']
        cardb.appendChild(p1)
        let p2 = document.createElement('p')
        p2.classList.add('card-text')
        p2.innerHTML ="Tulaj elérhetősége: "+ adat['elerhetoseg']
        cardb.appendChild(p2)
        let p3 = document.createElement('p')
        p3.classList.add('card-text')
        p3.innerHTML ="Érkezési időpont: "+ adat['takaritoErkezes']
        cardb.appendChild(p3)
        let p4 = document.createElement('p')
        p4.classList.add('card-text')
        p4.innerHTML ="Lakás mérete: "+ adat['terulet']+" m<sup>2</sup>"
        cardb.appendChild(p4)
        let p0 = document.createElement('p')
        p0.classList.add('card-text')
        p0.innerHTML = adat['id']
        p0.hidden = true
        cardb.appendChild(p0)
        let button1 = document.createElement('input')
        button1.type = "button"
        button1.classList.add("btn","btn-success")
        button1.id = "gomb"
        button1.setAttribute("onclick", "modositasModal("+"'"+adat['id']+"'"+")")
        button1.setAttribute("data-bs-toggle", "modal")
        button1.setAttribute("data-bs-target", "#modal_bef")
        button1.value = "Befejezve"
        cardb.appendChild(button1)
        card.appendChild(cardb)
        div.appendChild(card)
        valasz.appendChild(div)
    }
}
function modositasModal(id){
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
    //let span = document.getElementById('modal_felh_email')
    //span.innerText = ""
    //span.innerText = id

    let inputid = document.createElement('input')
    inputid.setAttribute("type", "hidden")
    inputid.value = id
    inputid.id = "takaritas_id"
    modalform.appendChild(inputid)

    
    
    let megjegyzes = document.createElement("textarea")
    megjegyzes.classList.add("form-control")
    megjegyzes.id = "megj"
    megjegyzes.rows = "5"
    megjegyzes.cols = "50"
    modalform.appendChild(megjegyzes)
}
async function mentes(){
    let megjegyzes = document.getElementById('megj')
    let tak_id = document.getElementById("takaritas_id")
        try {
            let kuldendo = {
                "id": tak_id.value,
                "megjegyzes": megjegyzes.value
            }
            let eredmeny = await fetch('../php/felhasznalok.php/mentes', {
                method : "PUT", 
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
        } catch (error) {
            console.log(error)
        }
    }
function valasz(adatok, torol){
    //let modalBody = document.getElementsByClassName('modal-body')[0]
    let p = document.getElementsByClassName('modal_valasz')
    if(torol == false){
        p[0].innerText = ""
        if(adatok['valasz']== "Sikeres művelet!") {
            p[0].innerText = adatok['valasz']
            p[0].style.border = '2px solid green'
            p[0].style.padding = '5px'
            
            document.getElementById('mentes').disabled = true
            adatokLekerese()
            //modalBody.appendChild(p)
        }
        else{
            p[0].innerText = adatok['valasz']
            p[0].style.border = '2px solid red'
            p[0].style.padding = '5px'
            //modalBody.appendChild(p)
        }
        p[0].hidden = false
    }
    else{
        p[1].innerText = ""
        if(adatok['valasz']== "Sikeres művelet!") {
            p[1].innerText = adatok['valasz']
            p[1].style.border = '2px solid green'
            document.getElementById('torles').disabled = true
            adatokLekerese()
            //modalBody.appendChild(p)
        }
        else{
            p[1].innerText = adatok['valasz']
            p[1].style.border = '2px solid red'
            //modalBody.appendChild(p)
        }
        p[1].style.padding = '5px'
        p[1].hidden = false
    }
    
}

window.addEventListener('load', adatokLekerese)
window.addEventListener('load', megyelekeres)
document.getElementById('mentes').addEventListener('click', mentes)