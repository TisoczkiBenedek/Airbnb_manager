async function adatokLekerese() {
    try {
        let eredmeny = await fetch('../php/felhasznalok.php/lekeres')
        if(eredmeny.ok){
            let adatok = await eredmeny.json();
            console.log(adatok)
            kiiras(adatok)
            tulajokKiiras(adatok)
        }
    } catch (error) {
        console.log(error)
    }
}
async function megyelekeres(){
    try {
        let eredmeny = await fetch('../php/felhasznalok.php/megyek')
        if(eredmeny.ok){
            let adatok = await eredmeny.json()
            feltoltes(adatok)
        }
        else{
            throw new error
        }
    } catch (error) {
        console.log(error)
    }
}
function feltoltes(adatok){
    let select = document.getElementById('megye')
    for (let adat of adatok) {
        let opt = document.createElement('option')
        opt.innerText = adat['megyeNev']
        opt.value = adat['megyeNev']
        select.appendChild(opt)
    }
}
function tulajokKiiras(adatok){
    const tulajok = new Set()
    for (let adat of adatok) {
        tulajok.add(adat['emailcim'])
    }
    let tulaj = document.getElementById('tulaj')
    for (let t of tulajok) {
        let opt = document.createElement('option')
        opt.innerText = t
        opt.value = t
        tulaj.appendChild(opt)
    }
}
function kiiras(adatok){
    let valasz = document.getElementById('valasz')
    valasz.innerText = ""
    for (let adat of adatok) {
        let div = document.createElement('div')
        div.classList.add("col-sm-12", "col-md-4", "col-lg-3", "mt-3", adat['emailcim'])
        let card = document.createElement('div')
        card.classList.add("card")
        let cardb = document.createElement('div')
        cardb.classList.add('card-body', adat['emailcim'])
        let h5 = document.createElement('h5')
        h5.classList.add("card-title")
        h5.innerText = adat['emailcim']
        cardb.appendChild(h5)
        let p = document.createElement('p')
        p.classList.add('card-text')
        p.innerHTML = adat['vezetekNev']
        cardb.appendChild(p)
        let p1 = document.createElement('p')
        p1.classList.add('card-text')
        p1.innerHTML = adat['keresztNev']
        cardb.appendChild(p1)
        let p2 = document.createElement('p')
        p2.classList.add('card-text')
        p2.innerHTML = adat['elerhetoseg']
        cardb.appendChild(p2)
        let p3 = document.createElement('p')
        p3.classList.add('card-text')
        p3.innerHTML = adat['megyeNev']
        cardb.appendChild(p3)
        let button1 = document.createElement('input')
        button1.type = "button"
        button1.classList.add("btn","btn-info")
        button1.id = "gomb"
        button1.setAttribute("onclick", "modositasModal("+"'"+adat['emailcim']+"'"+")")
        button1.setAttribute("data-bs-toggle", "modal")
        button1.setAttribute("data-bs-target", "#modal_modosit")
        cardb.appendChild(button1)
        let button = document.createElement('input')
        button.type = "button"
        button1.value = "Módosítás"
        button.classList.add("btn","btn-danger")
        button.value = "Törlés"
        button.setAttribute("onclick", "torlesModal("+"'"+adat['emailcim']+"'"+")")
        button.setAttribute("data-bs-toggle", "modal")
        button.setAttribute("data-bs-target", "#modal_torol")
        cardb.appendChild(button)
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
    let span = document.getElementById('modal_felh_email')
    span.innerText = ""
    span.innerText = id
    let label = document.createElement('label')
    label.classList.add('form-label')
    label.innerText = "Lakástulajdonos e-mail címe"
    modalform.appendChild(label)
    let cim = document.createElement("input")
    cim.id = "email"
    cim.type = "text"
    cim.value = id
    cim.classList.add("form-control")
    modalform.appendChild(cim)
    
    let label1 = document.createElement('label', 'mt-1')
    label1.classList.add('form-label')
    label1.innerText = "Felhasználó vezetékneve"
    modalform.appendChild(label1)
    let vnev = document.createElement("input")
    vnev.id = "vnev"
    vnev.type = "text"
    vnev.value = document.getElementsByClassName(id)[1].childNodes[1].innerText
    vnev.classList.add("form-control")
    modalform.appendChild(vnev)
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
            let eredmeny = await fetch('../php/felhasznalok.php/modositas', {
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
        } catch (error) {
            console.log(error)
        }
    }
    
}
function torlesModal(id){
    let idhely = document.getElementById('id_helye')
    idhely.innerText = ""
    idhely.innerText += id
    let p = document.getElementsByClassName('modal_valasz')
    p[1].innerText = ""
    p[1].style.border = "none"
    p[1].hidden = true
}
async function adattorles(){
    try {
        let kuldendo = {
            "id": document.getElementById('id_helye').innerText
        }
        let eredmeny = await fetch('../php/felhasznalok.php/torles', {
            method : "DELETE", 
            headers : {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(kuldendo)
        })
        if(eredmeny.ok){
            let adatok = await eredmeny.json()
            console.log(adatok)
            valasz(adatok, true)
            
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
function szures(){
    
    let megye = document.getElementById('megye')
    let azon = document.getElementById('tulaj')
    let card = document.getElementsByClassName('col-sm-12')
    let cardtext = document.getElementsByClassName('card-text')
    console.log(azon.value +"  "+ megye.value)
    if(megye.value == "" && azon.value == ""){
        for (let i = 0; i<card.length; i++) {
            card[i].hidden = false
        }
    }
    else{
        if(megye.value == ""){
            for (let i = 0; i<card.length; i++) {
                if(!card[i].className.includes(azon.value)){
                    card[i].hidden = true
                }
                else{
                    card[i].hidden = false
                }
            }
        }
        else if(azon.value== ""){
            for (let i = 0; i<cardtext.length; i++) {
                if(!cardtext[i].innerText.includes(megye.value)){
                    card[i].hidden = true
                }
                else{
                    card[i].hidden = false
                }
            }
        }
        else{
            for (let i = 0; i<cardtext.length; i++) {
                if(!cardtext[i].innerText.includes(megye.value) || !card[i].className.includes(azon.value)){
                    card[i].hidden = true
                }
                else{
                    card[i].hidden = false
                }
            }
        }
        
    }
}

window.addEventListener('load', adatokLekerese)
window.addEventListener('load', megyelekeres)
document.getElementById('mentes').addEventListener('click', modositas)
document.getElementById('torles').addEventListener('click', adattorles)
document.getElementById('form').addEventListener('input', szures)