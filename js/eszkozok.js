let adatok
async function eszkozLekeres() {
    try {
        let eredmeny = await fetch("../php/felhasznalok.php/eszkoz");
        if (eredmeny.ok) {
            adatok = await eredmeny.json()
            formFelt(false)
        }
    } catch (error) {
        console.log(error)
    }
}
let id_index = 1

function formFelt(tovabbihoz) {
    let eszkozok_db = adatok.length
    if (id_index <= eszkozok_db) {
        
        let form = document.getElementById("form")
        let select = document.createElement("select")
        let label1 = document.createElement("label")
        let label2 = document.createElement("label")
        let inputN = document.createElement("input")
        inputN.type = "number"
        inputN.id = "darab" + id_index
        inputN.classList.add("form-control", "w-50")
        label1.innerText = "Kérem válasszon eszközt!"
        label1.htmlFor = "eszk" + id_index
        label1.classList.add("form-label", "mt-2")
        label2.innerText = "Kérem válassza ki a rendelni kívánt mennyiséget!"
        label2.htmlFor = "darab" + id_index
        label2.classList.add("form-label", "mt-2")
        select.classList.add("form-select", "w-50")
        select.id = "eszk" + id_index
        id_index++
        for (let adat of adatok) {
            let opt = document.createElement("option")
            opt.value = adat["id"]
            opt.innerText = adat["nev"]+" "+adat["kiszereles"]
            select.appendChild(opt)
        }
        if (tovabbihoz == false) {
            let button = document.createElement("button")
            button.type = "button"
            button.classList.add("btn", "btn-secondary", "m-2")
            button.id = "gomb"
            button.innerText = "Igénylés leadása"
            let button1 = document.createElement("button")
            button1.type = "button"
            button1.classList.add("btn", "btn-info", "m-2")
            button1.id = "tovabbi"
            button1.innerText = "További eszközök hozzáadása"
            form.appendChild(label1)
            form.appendChild(select)
            form.appendChild(label2)
            form.appendChild(inputN)
            form.appendChild(button)
            form.appendChild(button1)
        }
        else {
            let elotte = document.getElementById("gomb")
            form.insertBefore(label1, elotte)
            form.insertBefore(select, elotte)
            form.insertBefore(label2, elotte)
            form.insertBefore(inputN, elotte)
        }
    }
    if(id_index == eszkozok_db+1){
        let gomb = document.getElementById("tovabbi")
        gomb.disabled = true
    }



}
function tovabbiFelt() {
    formFelt(true)
}
async function gomb() {
    let akt = document.getElementsByClassName("form-select")
    let aktdb = document.querySelectorAll("input")
    let kuldendo = []
    for(let i = 0; i< akt.length; i++){
        let id_darab = {
            "id": akt[i].value,
            "igenyelt": aktdb[i].value 
        }
        kuldendo.push(id_darab)
    }
    let eredmeny = await fetch("../php/felhasznalok.php/eszkozIgeny", {
        method : "POST",
        headers : {
            "Content-Type": "application/json"
        },
        body : JSON.stringify(kuldendo)
    })
    let valasz = await eredmeny.json()

}

window.addEventListener("load", eszkozLekeres)
setTimeout(() => document.getElementById("tovabbi").addEventListener("click", tovabbiFelt), 500)
setTimeout(() => document.getElementById("gomb").addEventListener("click", gomb), 500)

