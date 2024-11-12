function ellenorzes(){
    let email = document.getElementById('email').value
    let jelszo = document.getElementById('jelszo').value
    let vezetnev = document.getElementById('vnev').value 
    let keresztnev = document.getElementById('knev').value 
    let telefon = document.getElementById('telefonszam').value
    let radioeredmeny = document.querySelector('input:checked')
    let megye = document.getElementById('megye').value
    const nev = new RegExp(/^([A-ZÉÁŰÚŐÜÖÓÍ][a-zéáűúőóüöí]{1,})/)
    const tel = new RegExp(/(^\+?\d[0-9]{10})$/g)
    if(email=="" || vezetnev== "" || keresztnev == "" || telefon == "" || radioeredmeny == null || megye==""){
        alert("Kérem minden mezőt töltsön ki!")
        return
    }
    if(!email.includes("@")){
        alert("Hibás e-mail cím!")
        document.getElementById('email').style.border = "2px dashed red"
        return
    }
    else if(jelszo.length<8){
        document.getElementById('email').style.border = "2px solid green"
        alert("Kérem hosszabb jelszót adjon meg!")
        document.getElementById('jelszo').style.border = "2px dashed red"
        return
    }
    else if(nev.test(vezetnev)== false){
        document.getElementById('email').style.border = "2px solid green"
        document.getElementById('jelszo').style.border = "2px solid green"
        alert("Hibásan megadott név")
        document.getElementById('vnev').style.border = "2px dashed red"
        return
    }
    else if(nev.test(keresztnev)== false){
        document.getElementById('email').style.border = "2px solid green"
        document.getElementById('jelszo').style.border = "2px solid green"
        document.getElementById('vnev').style.border = "2px solid green"
        alert("Hibásan megadott keresztnév")
        document.getElementById('knev').style.border = "2px dashed red"
        return
    }
    else if(tel.test(telefon)==false){
        document.getElementById('email').style.border = "2px solid green"
        document.getElementById('jelszo').style.border = "2px solid green"
        document.getElementById('vnev').style.border = "2px solid green"
        document.getElementById('knev').style.border = "2px solid green"
        alert("Hibás telefonszám!")
        document.getElementById('telefonszam').style.border = "2px dashed red"
        return
    }
    else{
        document.getElementById('email').style.border = "2px solid green"
        document.getElementById('jelszo').style.border = "2px solid green"
        document.getElementById('vnev').style.border = "2px solid green"
        document.getElementById('knev').style.border = "2px solid green"
        document.getElementById('telefonszam').style.border = "2px solid green"
        document.getElementById('megye').style.border = "2px solid green"
        adatKuldes(email, jelszo, keresztnev, vezetnev, telefon, radioeredmeny.id, megye)
    }
}
async function adatKuldes(email, jelszo, knev, vnev, tel, tipus, megye) {
    try {
        let kuldendo = {
            "email": email,
            "jelszo": jelszo,
            "knev": knev, 
            "vnev": vnev,
            "telefon": tel,
            "tipus": tipus,
            "megye": megye
        }
        let eredmeny = await fetch('../php/regisztracio.php/regisztracio', {
            method : "POST",
            headers : {
                "Content-Type": "application/json"
            },
            body : JSON.stringify(kuldendo)
        })
        if(eredmeny.ok){
            let valasz = await eredmeny.json()
            valaszkiir(valasz)
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
        opt.value = adat['Id']
        select.appendChild(opt)
    }
}
async function megyelekeres(){
    try {
        let eredmeny = await fetch('../php/regisztracio.php/megyek')
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
function valaszkiir(valasz){
        document.getElementById('infok').classList.add("visually-hidden")
        document.getElementById('urlap').classList.add("visually-hidden")
        let div = document.createElement('div')
        div.innerText = valasz['valasz']
        document.getElementById("torzs").appendChild(div)
}
document.getElementById('gomb').addEventListener('click', ellenorzes)
window.addEventListener('load', megyelekeres)