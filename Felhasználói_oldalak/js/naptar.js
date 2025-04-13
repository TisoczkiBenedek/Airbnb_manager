document.addEventListener('DOMContentLoaded', async function() {
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
            return { html: info.event.title };
        },
        dateClick: function (info) {
            const clickedDate = new Date(info.dateStr);
            const ma = new Date();
            ma.setHours(0, 0, 0, 0);
        
            if (clickedDate < ma) {
                showToast("A múltbeli napokra nem lehet eseményt hozzáadni.", 'danger');
                return;
            }

            const existingEvents = calendar.getEvents();
            const hasConflict = existingEvents.some(event => {
                const eventStart = event.start ? new Date(event.start) : null;
                const eventEnd = event.end ? new Date(event.end) : null;
                
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
                selectedEvent = info.event;
                showModositasModal(info.event);
            }
        },
        eventDidMount: function(info) {
            if(info.event.title === 'Takarítás') {
                info.el.classList.add('fc-event-takaritas');
            }
        }
    });

    let selectedEvent = null;





    function showModositasModal(event) {
        const modal = document.getElementById('modositasModal');
        const closeButton = modal.querySelector('.close-button');
        const startInput = modal.querySelector('#ujStart');
        const endInput = modal.querySelector('#ujVeg');
        const takaritoSelect = modal.querySelector('#ujTakaritoSelect');
        const modositasForm = document.getElementById('esemenyModositasForm');
      
        modal.style.display = 'block';
        const megyeId = getSelectedMegyeId();
        // Betöltjük a takarítókat, és átadjuk a kiválasztott takarító ID-ját
        loadTakaritok(megyeId, event.extendedProps ? event.extendedProps.takarito_id : null, 'ujTakaritoSelect');
      
        const startTime = event.start.toLocaleTimeString('hu-HU', { hour: '2-digit', minute: '2-digit' });
        const endTime = event.end ? event.end.toLocaleTimeString('hu-HU', { hour: '2-digit', minute: '2-digit' }) : '';
        startInput.value = startTime;
        endInput.value = endTime;
      
        document.getElementById('torles').onclick = async function() {
        await deleteEvent(event.id);
        modal.style.display = 'none';
        }
      
        closeButton.onclick = function () {
        modal.style.display = 'none';
        }
      
        modositasForm.onsubmit = async function (e) {
            e.preventDefault();
            const newStartTime = document.getElementById('ujStart').value; // Javítva a selector
            const newEndTime = document.getElementById('ujVeg').value;     // Javítva a selector
            const newTakaritoId = modal.querySelector('#ujTakaritoSelect').value;
        
            if (newStartTime >= newEndTime) {
                showToast("A kezdeti időpont nem lehet később, mint a végső!", 'danger');
                return;
            }
        
            const startDate = new Date(`${event.start.getFullYear()}-${(event.start.getMonth() + 1).toString().padStart(2, '0')}-${event.start.getDate().toString().padStart(2, '0')}T${newStartTime}:00`);
            const endDate = new Date(`${event.end ? event.end.getFullYear() : event.start.getFullYear()}-${(event.end ? event.end.getMonth() + 1 : event.start.getMonth() + 1).toString().padStart(2, '0')}-${(event.end ? event.end.getDate() : event.start.getDate()).toString().padStart(2, '0')}T${newEndTime}:00`);
        
            const startLimit = new Date(`${startDate.getFullYear()}-${(startDate.getMonth() + 1).toString().padStart(2, '0')}-${startDate.getDate().toString().padStart(2, '0')}T08:00:00`);
            const endLimit = new Date(`${endDate.getFullYear()}-${(endDate.getMonth() + 1).toString().padStart(2, '0')}-${endDate.getDate().toString().padStart(2, '0')}T17:00:00`);
        
            if (startDate < startLimit || endDate > endLimit) {
                showToast("A kezdeti időpont nem lehet korábban 8:00 óránál, és a végső időpont pedig nem lehet később 17:00 óránál!", 'danger');
                return;
            }
        
            await updateEvent({
                id: event.id,
                start: startDate.toISOString(),
                end: endDate.toISOString(),
                takarito_id: newTakaritoId
            });
            modal.style.display = 'none';
        }
    }
      
        async function loadTakaritok(megyeId, selectedTakaritoId = null, selectId = 'takaritoSelect') {
            try {
                const valasz = await fetch(`../php/naptar.php?action=getTakaritok&megye_id=${megyeId}`);
                const responseData = await valasz.json();
                const select = document.getElementById(selectId);
                select.innerHTML = ''; // Select ürítése
      
        // 1. Üzenet kezelése
        if (responseData.valasz) { // Javítva a kulcs
            const option = document.createElement('option');
            option.textContent = responseData.valasz;
            option.disabled = true;
            select.appendChild(option);
            return;
        }
      
        // 2. Tömb kezelése
        if (Array.isArray(responseData)) {
            if (responseData.length === 0) {
                const option = document.createElement('option');
                option.textContent = "Nincsenek elérhető takarítók";
                option.disabled = true;
                select.appendChild(option);
            } else {
                responseData.forEach(takarito => {
                    const option = document.createElement('option');
                    option.value = takarito.id;
                    option.textContent = takarito.nev;
                    if (selectedTakaritoId && takarito.id === selectedTakaritoId) {
                        option.selected = true;
                    }
                select.appendChild(option);
                });
            }
            return;
        }
      
        // 3. Egyéb esetek
        const option = document.createElement('option');
        option.textContent = "Nincsenek elérhető takarítók ebben a megyében";
        option.disabled = true;
        select.appendChild(option);
      
        } catch (error) {
            console.error('Hiba a takarítók betöltésekor:', error);
            showToast('Hiba történt a takarítók betöltésekor.', 'danger');
        }
    }


    async function updateEvent(eventData) {
        try {
            const eredmeny = await fetch('../php/naptar.php?action=updateTakaritas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: eventData.id,
                    start: adjustedStart.toISOString(),
                    end: adjustedEnd.toISOString(),
                    takarito_id: eventData.takarito_id
                })
            });
    
            if (!eredmeny.ok) {
                throw new Error(`HTTP hiba: ${eredmeny.status} ${eredmeny.statusText}`);
            }
    
            const data = await eredmeny.json();
            if (data.success) {
                selectedEvent.setStart(adjustedStart);
                selectedEvent.setEnd(adjustedEnd);
                showToast("Takarítás sikeresen módosítva!", 'success');
            } else {
                showToast("Hiba történt a takarítás módosítása során: " + data.error, 'danger');
            }
        } catch (error) {
            console.error('Hiba történt a takarítás módosítása során:', error);
            showToast("Hiba történt a takarítás módosítása során.", 'danger');
        }
    }

async function deleteEvent(eventId) {
    try {
        const eredmeny = await fetch('../php/naptar.php?action=deleteTakaritas', {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
            },
            body: JSON.stringify({eventId:eventId})
        });

        const data = await eredmeny.json();
        if(data.success) {
            if (selectedEvent) { // Ellenőrzés hozzáadva
                    selectedEvent.remove();
            }
                showToast("Takarítás sikeresen törölve!", 'success');
        } else {
            showToast("Hiba történt a törlés során!", 'danger');
        }
    } catch (error) {
        console.error('Hiba történt:', error);
    }
}
    
    calendar.render();

    function getSelectedMegyeId() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('megye_id');
    }

    function modalNyitas(date) {
        const modal = document.getElementById("ujEsemenyModal");
        const closeButton = modal.querySelector('.close-button');
        const form = document.getElementById('ujEsemenyForm');

        modal.style.display = 'block';
        const megyeId = getSelectedMegyeId();
        loadTakaritok(megyeId);

        closeButton.onclick = function () {
            modal.style.display = 'none';
        }

        form.onsubmit = async function (e) {
            e.preventDefault();      
            const startTime = document.getElementById('start').value;
            const endTime = document.getElementById('end').value;
            
            console.log(startTime, endTime);

            if (startTime >= endTime) {
                showToast("A kezdeti időpont nem lehet később, mint a végső!", 'danger');
                return;
            }
            
            const startDateTime = new Date(`${date}T${startTime}:00`);
            const endDateTime = new Date(`${date}T${endTime}:00`);
            
            const startLimit = new Date(`${date}T08:00:00`);
            const endLimit = new Date(`${date}T17:00:00`);
            
            if (startDateTime < startLimit || endDateTime > endLimit) {
                showToast("A kezdeti időpont nem lehet korábban 8:00 óránál, és a végső időpont pedig nem lehet később 17:00 óránál!", 'danger');
                return;
            }

            calendar.addEvent({
                title: 'Takarítás',
                start: startDateTime,
                end: endDateTime,
            });

            modal.style.display = 'none';
            await saveEvent({ start: startDateTime, end: endDateTime });
        }
    }

    async function saveEvent(event) {
        const takaritoId = document.getElementById('takaritoSelect').value;
        const lakasId = new URLSearchParams(window.location.search).get('lakas_id');
    
        if (!takaritoId) {
            showToast('Válassz takarítót!', 'danger');
            return;
        }
    
        // 2 óra hozzáadása ezredmásodpercben (2 * 60 * 60 * 1000)
        const twoHoursInMillis = 7200000;
        const adjustedStart = new Date(event.start.getTime() + twoHoursInMillis);
        const adjustedEnd = new Date(event.end.getTime() + twoHoursInMillis);
    
        try {
            const adat = {
                start: adjustedStart.toISOString(),
                end: adjustedEnd.toISOString(),
                lakas_id: lakasId,
                takarito_id: takaritoId
            };
    
            console.log("JS - Elküldött start (2 órával hozzáadva):", adat.start);
            console.log("JS - Elküldött end (2 órával hozzáadva):", adat.end);
    
            const eredmeny = await fetch('../php/naptar.php?action=esemenyMentes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(adat)
            });
    
            if (!eredmeny.ok) {
                throw new Error(`HTTP hiba: ${eredmeny.status} ${eredmeny.statusText}`);
            }
    
            const data = await eredmeny.json();
    
            if (data.success) {
                calendar.refetchEvents();
                showToast("Takarítás sikeresen megrendelve!", 'success');
            } else {
                showToast("Hiba történt a takarítás megrendelése során: " + data.error, 'danger');
            }
        } catch (error) {
            console.error('Hiba történt a takarítás megrendelése során:', error);
            showToast("Hiba történt a takarítás megrendelése során.", 'danger');
        }
    }

    // Event betöltés
    const urlParams = new URLSearchParams(window.location.search);
    const lakasId = urlParams.get('lakas_id');

    if (lakasId) {
        console.log("A lakasId az URL-ben:", lakasId);

        try {
            const eredmeny = await fetch(`../php/naptar.php?lakas_id=${lakasId}`);

            if (!eredmeny.ok) {
                throw new Error(`HTTP hiba: ${eredmeny.status} ${eredmeny.statusText}`);
            }

            const events = await eredmeny.json();

            if (events.error) {
                console.error("Hiba a válaszban:", events.error);
                showToast("Hiba történt: " + events.error);
            } else {
                calendar.addEventSource(events);
            }
        } catch (error) {
            console.error("Hiba történt:", error);
        }
    }
});

// Profilnév lekérése és megjelenítése
async function loadProfilNev() {
    try {
        const response = await fetch('../php/naptar.php?action=getProfilAdat');
        const data = await response.json();
        document.getElementById('profilNev').innerText = data.profilNev; // Módosított kulcs
    } catch (error) {
        console.error('Hiba:', error);
    }
}

// Toast inicializálása
const toastElement = document.getElementById('toast');
const toastBody = toastElement.querySelector('.toast-body');
const toast = new bootstrap.Toast(toastElement);

function showToast(message, type = 'danger') {
    toastBody.textContent = message;
    toastElement.classList.remove('bg-danger', 'bg-success');
    toastElement.classList.add(`bg-${type}`);
    toast.show();
}

// Oldal betöltésekor futtatjuk
document.addEventListener('DOMContentLoaded', loadProfilNev);

function vissza() {
    window.location.href = '../html/lakasok.html'; 
}