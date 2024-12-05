// JavaScript a felugró ablak kezeléséhez 
const felvitel = document.getElementById("felvitel"); 
const eszkozFelvitel = document.getElementById("eszkozFelvitele"); 
const span = document.getElementsByClassName("close")[0]; 


eszkozFelvitel.onclick = function() { 
    felvitel.style.display = "block"; 
} 


span.onclick = function() { 
    felvitel.style.display = "none"; 
} 


window.onclick = function(event) { 
    if (event.target == felvitel) { 
        felvitel.style.display = "none"; 
    } 
}

form.onsubmit = function() { 
    setTimeout(() => { 
        form.reset(); 
        felvitel.style.display = "none"; 
    }, 100); 
}