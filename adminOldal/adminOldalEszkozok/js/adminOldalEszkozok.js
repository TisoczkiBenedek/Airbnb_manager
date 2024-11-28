// JavaScript a felugró ablak kezeléséhez 
let felvitel = document.getElementById("felvitel"); 
let eszkozFelvitel = document.getElementById("eszkozFelvitele"); 
let span = document.getElementsByClassName("close")[0]; 


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