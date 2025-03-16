document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'hu',
        firstDay: 1,
        events: [],
        buttonText: {
            today: 'Mai nap'
        },
        eventContent: function(info) {
            // Ha az esemény címe "Takarítás", akkor hozzáadjuk a kezdő és végző időpontokat
            if (info.event.title === 'Takarítás') {
                const startTime = info.event.start ? info.timeText : '';
                const endTime = info.event.end ? info.event.end.toLocaleTimeString('hu-HU', { hour: '2-digit', minute: '2-digit' }) : '';
                return {
                    html: `
                        <div class="fc-event-takaritas">
                            <div class="fc-event-title">${info.event.title}</div>
                            <div class="fc-event-time">${startTime} - ${endTime}</div>
                        </div>
                    `
                };
            }
            // Egyéb események esetén alapértelmezett megjelenítés
            return { html: info.event.title };
        },
        dateClick: function (info) {
            const clickedDate = new Date(info.dateStr);
            const ma = new Date();
            ma.setHours(0, 0, 0, 0);
        
            // Múltbeli dátum letiltása
            if (clickedDate < ma) {
                showToast("A múltbeli napokra nem lehet eseményt hozzáadni.", 'danger');
                return;
            }

            // Események ellenőrzése
            const existingEvents = calendar.getEvents();
            const hasConflict = existingEvents.some(event => {
                const eventStart = event.start ? new Date(event.start) : null;
                const eventEnd = event.end ? new Date(event.end) : null;
                
                // Teljes dátum-ellenőrzés időpontokkal
                return (
                    (clickedDate >= eventStart && clickedDate <= eventEnd) ||
                    (eventStart.toDateString() === clickedDate.toDateString())
                );
            });
        
            if (hasConflict) {
                showToast("Erre a napra már van esemény.", 'danger');
                return;
            }
        
            modalNyitas(info.dateStr);
        },
        eventClick: function(info) {
            if(info.event.title === 'Takarítás') {
                showDeleteModal(info.event);
            }
        },
        eventDidMount: function(info) {
            if(info.event.title === 'Takarítás') {
                info.el.classList.add('fc-event-takaritas');
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
            e.preventDefault();
            const startTime = document.getElementById('start').value;
            const endTime = document.getElementById('end').value;
            
            // Kezdési időpont nem lehet később, mint a befejezési
            if (startTime >= endTime) {
                showToast("A kezdeti időpont nem lehet később, mint a végső!", 'danger');
                return;
            }
            
            // Dátum és idő összeállítása
            const startDateTime = new Date(`${date}T${startTime}:00`);
            const endDateTime = new Date(`${date}T${endTime}:00`);
            
            // Ellenőrzés
            if (startDateTime < new Date(`${date}T08:00:00`) && endDateTime > new Date(`${date}T17:00:00`)) {
                showToast("A kezdeti időpont nem lehet korábban 8:00 óránál, és a végső időpont pedig nem lehet később 17:00 óránál!", 'danger');
                return;
            }

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
        const takaritoId = document.getElementById('takaritoSelect').value;
        const lakasId = new URLSearchParams(window.location.search).get('lakas_id');
        
        if (!takaritoId) {
            alert('Válassz takarítót!');
            return;
        }
        

        //adatok
        const adat = {
            start: event.start,
            end: event.end,
            lakas_id: lakasId,
            takarito_id: takaritoId
        }

        fetch('../php/naptar.php?action=esemenyMentes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(adat)
        })
        .then(eredmeny => eredmeny.json())
        .then(adat => {
            if (adat.success) {
                console.log('Takarítás sikeresen megrendelve', 'success');
            } else {
                console.error('Hiba a takarítás megrendelése során: ', adat.errorm, 'danger');
            }
        })
        .then(adat => {
            if (adat.success) {
                calendar.refetchEvents(); // Frissítés hozzáadva
                showToast("Takarítás sikeresen megrendelve!", 'success');
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
