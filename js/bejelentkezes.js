function ellenorzes() {
    let emailcim = document.getElementById('email').value
    let jelszo = document.getElementById('jelszo').value
    let vhely = document.getElementById("vhely")
    vhely.innerText = ""
    if (email = "" || jelszo == "") {
        vhely.innerText = "Kérem minden mezőt töltsön ki!"
        document.getElementById('email').style.border = "2px dashed red"
        document.getElementById('jelszo').style.border = "2px dashed red"
        vhely.hidden = false
        return
    }
    else if (!emailcim.includes("@")) {
        vhely.innerText = "Hibás e-mail cím!"
        document.getElementById('email').style.border = "2px dashed red"
        document.getElementById('jelszo').style.border = "none"
        vhely.hidden = false
        return
    }
    else if (jelszo.length < 8) {
        vhely.innerText = "Túl rövid jelszó!"
        document.getElementById('jelszo').style.border = "2px dashed red"
        document.getElementById('email').style.border = "none"
        vhely.hidden = false
        return
    }
    else {
        adatKuldes(emailcim, jelszo)
        document.getElementById('jelszo').style.border = "none"
        document.getElementById('email').style.border = "none"
        vhely.hidden = true
    }
}
async function adatKuldes(email, jelszo) {
    try {
        let kuldendo = {
            "email": email,
            "jelszo": jelszo
        }
        let eredmeny = await fetch('../php/bejelentkezes.php/bejelentkezes', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(kuldendo)
        })

        let adatok = await eredmeny.json()
        felhasznaloInformalas(adatok)


    } catch (error) {
        console.log(error)
    }
}
function felhasznaloInformalas(adatok) {
    let vhely = document.getElementById("vhely")
    if (adatok.valasz) {
        vhely.innerText = adatok["valasz"]
        vhely.hidden = false
    }
    else {
        console.log(adatok["felhTipus"])
        if (adatok.felhTipus) {
            console.log(adatok)

            const felhTipus = adatok.felhTipus;

            if (felhTipus == 'tulajdonos') {
                window.open('../Felhasználói_oldalak/html/lakasok.html', '_parent');
            } else if(felhTipus == 'takarito') {
                window.open('../Felhasználói_oldalak/html/felhasznalok.html', '_parent');
            }
            else{
                window.open('../Admin_oldalak/html/felhasznalok.html', '_parent')
            }
        }
    }
}
function enterBevitel(event) {
    if (event.key === "Enter") {
        ellenorzes()
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
document.getElementById('gomb').addEventListener('click', ellenorzes)
document.getElementById('jelszo').addEventListener("keypress", enterBevitel)
document.getElementById('email').addEventListener("keypress", enterBevitel)
document.getElementById('szem').addEventListener("click", jelszomegj)