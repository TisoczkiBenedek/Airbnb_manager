document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'hu',
        firstDay: 1,
        events: [],
    });

    calendar.render();

    const urlParams = new URLSearchParams(window.location.search);
    const lakasId = urlParams.get('lakas_id');

    if (lakasId) {
        console.log("A lakasId az URL-ben:", lakasId); // Ellenőrzés
        fetch(`../php/naptar.php?lakas_id=${lakasId}`)
            .then(eredmeny => {
                if (!eredmeny.ok) {
                    throw new Error(`HTTP hiba: ${eredmeny.status} ${eredmeny.statusText}`);
                }
                return eredmeny.json();
            })
            .then(events => {
                if (events.error) {
                    console.error("Hiba a válaszban:", events.error);
                    alert("Hiba történt: " + events.error); // felhasználó tájékoztatása
                } else {
                    events.forEach(event => {
                        event.allDay = true; // Egész napos beállítás
                    });
                    calendar.addEventSource(events);
                }
            })
            .catch(error => {
                console.error("Hiba történt:", error);
                //alert("Nem sikerült betölteni az eseményeket. Kérlek, próbáld újra később: " + error.message);
            });
    }

    
});

// Profilnév lekérése és megjelenítése
function loadProfilNev() {
    fetch('../php/naptar.php?action=getProfilNev')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP hiba! Státusz: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.profilNev) {
                document.getElementById('profilNev').innerText = data.profilNev;
            }
        })
        .catch(error => {
            console.error('Hiba a profilnév betöltésekor:', error);
            document.getElementById('profilNev').innerText = "Hiba a profilnév betöltésekor";
        });
}

// Oldal betöltésekor futtatjuk
document.addEventListener('DOMContentLoaded', loadProfilNev);

function vissza(){
    window.location.href = '../html/lakasok.html'; 
}