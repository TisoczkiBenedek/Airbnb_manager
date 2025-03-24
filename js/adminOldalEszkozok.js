document.addEventListener("DOMContentLoaded", function() {
    // Profilnév betöltése
    const profilNevElement = document.getElementById("profilNev");
    if (profilNevElement) {
        // Itt lehet session-ből vagy localStorage-ből betölteni a nevet
        const userName = "Admin"; 
        profilNevElement.textContent = userName;
    }

    // Kijelentkezés funkció
    window.kijelentkezes = function() {
        fetch('../php/kijelentkezes.php')
            .then(response => {
                if(response.ok) {
                    window.location.href = '../html/bejelentkezes.html';
                }
            });
    };
});