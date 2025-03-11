document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'hu',
        firstDay: 1,
        events: [],
        dateClick: function (info) {
            const clickedDate = new Date(info.dateStr); // A kattintott dátum
            const ma = new Date(); // Az aktuális dátum
            ma.setHours(0, 0, 0, 0); // Az időpontot nullázd ki

            // Csak akkor jelenítsd meg a modalt, ha a kattintott dátum nem korábbi, mint a mai
            if (clickedDate >= ma) {
                modalNyitas(info.dateStr);
            } else {
                showToast("A múltbeli napokra nem lehet eseményt hozzáadni.", 'danger');
            }

        }
    });

    calendar.render();

    //megyeId
    function getSelectedMegyeId() {
        const urlParams = new URLSearchParams(window.location.search);
        const megyeId = urlParams.get('megye_id');

        return megyeId
    }

    function modalNyitas(date) {
        const modal = document.getElementById("ujEsemenyModal");
        const closeButton = modal.querySelector('.close-button');
        const form = document.getElementById('ujEsemenyForm');

        // Modal megjelenítés
        modal.style.display = 'block';

        //takarítók betöltése
        const megyeId = getSelectedMegyeId();
        loadTakaritok(megyeId);

        // Modal becsukás
        closeButton.onclick = function () {
            modal.style.display = 'none';
        }

        form.onsubmit = function (e) {
            e.preventDefault();
            const startTime = document.getElementById('start').value;
            const endTime = document.getElementById('end').value;

            // Dátum és idő összeállítása
            const startDateTime = `${date}T${startTime}:00`;
            const endDateTime = `${date}T${endTime}:00`;

            // Esemény hozzáadása a naptárhoz
            calendar.addEvent({
                title: 'Takarítás',
                start: startDateTime,
                end: endDateTime,
            });

            // Modal 
            modal.style.display = 'none';

            // Esemény mentése a backendre
            saveEvent({ start: startDateTime, end: endDateTime });
        }
    }

    function saveEvent(event) {
        fetch('../php/naptar.php?action=esemenyMentes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(event)
        })
        .then(eredmeny => eredmeny.json())
        .then(adat => {
            if (adat.success) {
                console.log('Takarítás sikeresen megrendelve');
            } else {
                console.error('Hiba a takarítás megrendelése során: ', adat.error);
            }
        })
        .catch(error => {
            console.error('Hiba történt:', error);
        });
    }

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

async function loadTakaritok(megyeId) {
    try {
        const valasz = await fetch(`../php/naptar.php?action=getTakaritok&megye_id=${megyeId}`);
        
        if (!valasz.ok) {
            throw new Error(`HTTP hiba! Státusz: ${valasz.status}`);
        }

        const takaritok = await valasz.json();
        const select = document.getElementById('takaritoSelect');
        select.innerHTML = ''; // Töröljük a korábbi opciókat

        // Ha nincsenek takarítók, egy üzenetet jelenítünk meg
        if (takaritok.message) {
            const option = document.createElement('option');
            option.textContent = takaritok.message;
            select.appendChild(option);
            return;
        }

        // Takarítók hozzáadása a selecthez
        takaritok.forEach(takarito => {
            const option = document.createElement('option');
            option.value = takarito.id;
            option.textContent = takarito.nev;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Hiba a takarítók betöltésekor:', error);
        alert('Hiba történt a takarítók betöltésekor.');
    }
}

// Toast inicializálása
const toastElement = document.getElementById('toast');
const toastBody = toastElement.querySelector('.toast-body');
const toast = new bootstrap.Toast(toastElement);

// Toast megjelenítése
function showToast(message, type = 'danger') {
    toastBody.textContent = message;
    toastElement.classList.remove('bg-danger', 'bg-success');
    toastElement.classList.add(`bg-${type}`);
    toast.show();
}

// Oldal betöltésekor futtatjuk
document.addEventListener('DOMContentLoaded', loadProfilNev);

function vissza(){
    window.location.href = '../html/lakasok.html'; 
}