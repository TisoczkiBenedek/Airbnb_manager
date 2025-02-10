document.addEventListener('DOMContentLoaded', function () {
    $(document).ready(function(){
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'month', // Alapértelmezett nézet: hónap
            events: function (start, end, timezone, callback) {
                // .ics fájl betöltése és feldolgozása
                fetch('../uploads/lakas_13/naptar/1739137973_ical_jan_feb_eventsics')
                    .then(response => response.text())
                    .then(icsData => {
                        const jcalData = ICAL.parse(icsData);
                        const comp = new ICAL.Component(jcalData);

                        // Események kinyerése
                        const events = comp.getAllSubcomponents('vevent').map(vevent => {
                            const event = new ICAL.Event(vevent);
                            return {
                                title: event.summary,
                                start: event.startDate.toJSDate(),
                                end: event.endDate.toJSDate(),
                                description: event.description
                            };
                        });

                        // Események hozzáadása a naptárhoz
                        callback(events);
                    })
                    .catch(error => {
                        console.error('Hiba az .ics fájl betöltésekor:', error);
                    });
            }
        });
    }) 
});