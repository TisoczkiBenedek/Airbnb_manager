function ellenorzes(){
    let email = document.getElementById('email').value
    let jelszo = document.getElementById('jelszo').value
    let vezetnev = document.getElementById('vnev').value 
    let keresztnev = document.getElementById('knev').value 
    let telefon = document.getElementById('telefonszam').value
    let radioeredmeny = document.querySelector('input:checked')
    console.log(jelszo)
    const nev = new RegExp(/^([A-ZÉÁŰÚŐÜÖÓÍ])\w/)
    if(email=="" || vezetnev== "" || keresztnev == "" || telefon == "" || radioeredmeny == null){
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
}
document.getElementById('gomb').addEventListener('click', ellenorzes)