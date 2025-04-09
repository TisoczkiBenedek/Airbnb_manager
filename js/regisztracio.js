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
    let visszajelz = document.getElementById("visszajelz")
    let tbody = document.getElementById("tbody")
    let toast = document.getElementById("toast")
    visszajelz.innerText = ""
    toast.classList = ""
    tbody.innerText = ""
    toast.classList.add("bg-danger-subtle", "toast")
    const toastBootstrap = new bootstrap.Toast(toast)
    if(email=="" || vezetnev== "" || keresztnev == "" || telefon == "" || radioeredmeny == null || megye==""){
        visszajelz.innerText = "Hiba történt!"
        tbody.innerText= "Kérem minden mezőt ötlsön ki!"
        toastBootstrap.show()
        return
    }
    else if(!email.includes("@")){
        visszajelz.innerText = "Hiba történt"
        tbody.innerText= "Hibásan megadott e-mail cím!"
        toastBootstrap.show()
        document.getElementById('email').style.border = "2px dashed red"
        return
    }
    else if(jelszo.length<8){
        document.getElementById('email').style.border = "2px solid green"
        visszajelz.innerText = "Hiba történt"
        tbody.innerText= "Túl rövid a jelszó! (legalább 8 karakter)"
        toastBootstrap.show()
        document.getElementById('jelszo').style.border = "2px dashed red"
        return
    }
    else if(nev.test(vezetnev)== false){
        document.getElementById('email').style.border = "2px solid green"
        document.getElementById('jelszo').style.border = "2px solid green"
        visszajelz.innerText = "Hiba történt"
        tbody.innerText= "Hibásan megadott név!"
        toastBootstrap.show()
        document.getElementById('vnev').style.border = "2px dashed red"
        return
    }
    else if(nev.test(keresztnev)== false){
        document.getElementById('email').style.border = "2px solid green"
        document.getElementById('jelszo').style.border = "2px solid green"
        document.getElementById('vnev').style.border = "2px solid green"
        visszajelz.innerText = "Hiba történt"
        tbody.innerText= "Hibásan megadott név!"
        toastBootstrap.show()
        document.getElementById('knev').style.border = "2px dashed red"
        return
    }
    else if(tel.test(telefon)==false){
        document.getElementById('email').style.border = "2px solid green"
        document.getElementById('jelszo').style.border = "2px solid green"
        document.getElementById('vnev').style.border = "2px solid green"
        document.getElementById('knev').style.border = "2px solid green"
        visszajelz.innerText = "Hiba történt"
        tbody.innerText=  "Hibásan megadott telefonszám!"
        toastBootstrap.show()
        document.getElementById('telefonszam').style.border = "2px dashed red"
        document.getElementById('telefonszam').setAttribute("placeholder", "+36301234567 formában!")
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
function valaszkiir(valasz){
    let visszajelz = document.getElementById("visszajelz")
    let tbody = document.getElementById("tbody")
    let toast = document.getElementById("toast")
    visszajelz.innerText = ""
    toast.classList = ""
    tbody.innerText = ""
    if(valasz['valasz']== "Nincs"){
        visszajelz.innerText = "Hiba történt"
        tbody.innerText= "Már van ezzel az e-mail címmel regisztrált felhasználó! Kérem próbáljon meg bejelentkezni!"
        toast.classList.add("bg-danger-subtle", "toast")
        const toastBootstrap = new bootstrap.Toast(toast)
        toastBootstrap.show()
    }
    else{
        //document.getElementById('infok').classList.add("visually-hidden")
        //document.getElementById('urlap').classList.add("visually-hidden")
        //let div = document.createElement('div')
        //div.innerText = valasz['valasz']
        //document.getElementById("torzs").appendChild(div)
        visszajelz.innerText = "Siker!"
        tbody.innerText= valasz['valasz']
        toast.classList.add("bg-success-subtle", "toast")
        const toastBootstrap = new bootstrap.Toast(toast)
        toastBootstrap.show()
        setTimeout(()=>{window.open('../alapoldal.html', '_parent')}, 6000)
        
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
function feltoltes(adatok){
    let select = document.getElementById('megye')
    for (let adat of adatok) {
        let opt = document.createElement('option')
        opt.innerText = adat['megyeNev']
        opt.value = adat['id']
        select.appendChild(opt)
    }
}
function jelszomegj(){
    let mezo =  document.getElementById("jelszo")
    if(mezo.type == "text"){
        mezo.type = "password"
    }
    else{
        mezo.type = "text"
    }
}
window.addEventListener('load', megyelekeres)
document.getElementById('gomb').addEventListener('click', ellenorzes)
document.getElementById('szem').addEventListener("click", jelszomegj)