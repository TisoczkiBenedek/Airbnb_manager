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
function formFelt(tovabbihoz) {
    let form = document.getElementById("form")
    let select = document.createElement("select")
    let label = document.createElement("label")
    label.innerText = "Kérem válasszon eszközt"
    label.htmlFor = "eszk"
    label.classList.add("form-label", "mt-2")
    select.classList.add("form-select", "w-50")
    select.id = "eszk"
    for (let adat of adatok) {
        let opt = document.createElement("option")
        opt.value = adat["id"]
        opt.innerText = adat["nev"]
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
        form.appendChild(label)
        form.appendChild(select)

        form.appendChild(button)
        form.appendChild(button1)
    }
    else{
        let elotte = document.getElementById("gomb")
        form.insertBefore(label, elotte)
        form.insertBefore(select, elotte)
    }



}
function tovabbi() {
    formFelt(true)

}

window.addEventListener("load", eszkozLekeres)
document.getElementById("tovabbi").addEventListener("click", tovabbi)