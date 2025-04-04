document.addEventListener('DOMContentLoaded', function() {
    loadProfilNev();
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
    });

    // Események manuális betöltése
    fetch('../php/naptarTakarito.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP hiba! Státusz: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            console.log('Válasz szövege:', text);
            const data = JSON.parse(text);
            console.log('Események betöltve:', data);
            calendar.addEventSource(data);
            calendar.render();
        })
        .catch(error => {
            console.error('Hiba az események betöltésekor:', error);
        });

    calendar.render();
});

// Profilnév lekérése és megjelenítése
function loadProfilNev() {
    fetch('../php/naptarTakarito.php?action=getProfilNev')
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

async function kijelentkezes() {
    try {
        const eredmeny = await fetch('../php/kijelentkezes.php', {
            method: 'POST',
            credentials: 'include'
        });

        if (eredmeny.ok) {
            const adat = await eredmeny.json();

            if (adat.success) {
                window.location.href = '../../html/bejelentkezes.html';
            } else {
                console.error('Hiba a kijelentkezés során:', adat.message);
            }
        } else {
            throw new Error(`HTTP hiba! Státusz: ${eredmeny.status}`);
        }
    } catch (error) {
        console.error('Hiba a kijelentkezés során: ', error);
    }
}