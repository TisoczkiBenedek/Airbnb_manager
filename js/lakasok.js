async function adatokLekerese() {
    try {
        let eredmeny = await fetch('../php/lakasok.php/lekeres')
        if(eredmeny.ok){
            let adatok = await eredmeny.json();
            console.log(adatok)
            kiiras(adatok)
        }
    } catch (error) {
        console.log(error)
    }
}
function kiiras(adatok){
    let valasz = document.getElementById('valasz')
    for (let adat of adatok) {
        let div = document.createElement('div')
        div.classList.add("col-sm-12", "col-md-4", "col-lg-3", "mt-3")
        let card = document.createElement('div')
        card.classList.add("card")
        let img = document.createElement('img')
        img.src = adat['kepek']
        img.classList.add("card-img-top")
        card.appendChild(img)
        let cardb = document.createElement('div')
        cardb.classList.add('card-body')
        let h5 = document.createElement('h5')
        h5.classList.add("card-title", adat['id'])
        h5.innerText = adat['tulajdonosEmail']
        cardb.appendChild(h5)
        let p = document.createElement('p')
        p.classList.add('card-text')
        p.innerHTML = adat['lakcim']+"<br>"+adat['megyeNev']
        cardb.appendChild(p)
        let button1 = document.createElement('input')
        button1.type = "button"
        button1.classList.add("btn","btn-info")
        button1.id = "gomb"
        button1.setAttribute("onclick", "modositasModal("+adat['id']+")")
        button1.setAttribute("data-bs-toggle", "modal")
        button1.setAttribute("data-bs-target", "#modal_modosit")
        cardb.appendChild(button1)
        let button = document.createElement('input')
        button.type = "button"
        button1.value = "Módosítás"
        button.classList.add("btn","btn-danger")
        button.value = "Törlés"
        button.setAttribute("onclick", "torlesModal("+adat['id']+")")
        button.setAttribute("data-bs-toggle", "modal")
        button.setAttribute("data-bs-target", "#modal_torol")
        cardb.appendChild(button)
        card.appendChild(cardb)
        div.appendChild(card)
        valasz.appendChild(div)
    }
}
function modositasModal(id){
    let modalform = document.getElementById('modal_form')
    modalform.innerText = ""
    let modalcim = document.getElementById('modal_cim')
    modalcim.innerText = ""
    let span = document.createElement('span')
    span.innerText = "Lakás Id: "
    modalcim.appendChild(span)
    modalcim.innerText += id
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
                "id": document.getElementById('id_helye').innerText,
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
}


window.addEventListener('load', adatokLekerese)
document.getElementById('mentes').addEventListener('click', modositas)