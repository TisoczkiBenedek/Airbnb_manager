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
    })
})