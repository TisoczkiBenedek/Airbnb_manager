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
        div.classList.add("col-sm-12", "col-md-4", "col-lg-3")
        let card = document.createElement('div')
        card.classList.add("card")
        let img = document.createElement('img')
        img.src = adat['kepek']
        img.classList.add("card-img-top")
        card.appendChild(img)
        let cardb = document.createElement('div')
        cardb.classList.add('card-body')
        let 
    }
}