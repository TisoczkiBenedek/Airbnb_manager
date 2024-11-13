function ellenorzes(){
    let emailcim = document.getElementById('email').value 
    let jelszo = document.getElementById('jelszo').value 
    if(email = "" || jelszo== ""){
        alert("Kérem minden mezőt töltsön ki!")
        return
    }
    else if(!emailcim.includes("@")){
        alert("Hibás e-mail cím!")
        document.getElementById('email').style.border = "2px dashed red"
        return
    }
    else if(jelszo.length<8){
        alert("Túl rövid jelszó!")
        document.getElementById('email').style.border = "2px dashed red"
        return
    }
    else{
        adatKuldes(emailcim, jelszo)
    }
}
async function adatKuldes(email, jelszo) {
    try {
        let kuldendo = {
            "email": email, 
            "jelszo": jelszo
        }
        let eredmeny = await fetch('../php/bejelentkezes.php/bejelentkezes', {
            method : "POST",
            headers : {
                "Content-Type": "application/json"
            },
            body : JSON.stringify(kuldendo)
        })
        if(eredmeny.ok){
            let valasz = await eredmeny.json()
            console.log(valasz)
        }
        else{
            throw new error
        }
    } catch (error) {
        console.log(error)
    }
    
    
}
document.getElementById('gomb').addEventListener('click', ellenorzes)