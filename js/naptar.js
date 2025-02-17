// document.addEventListener('DOMContentLoaded', function() {
//     const calendarEl = document.getElementById('calendar');
//     const calendar = new FullCalendar.Calendar(calendarEl, {
//         initialView: 'dayGridMonth',
//         locale: 'hu',
//         firstDay: 1,
//         events: [], // Kezdetben üres események
//     });

//     calendar.render();

//     // URL paraméterek kiolvasása
//     const urlParams = new URLSearchParams(window.location.search);
//     const lakasId = urlParams.get('lakas_id');

//     if (lakasId) {
//         // Események lekérése a PHP backendről
//         fetch(`../php/naptar.php?lakas_id=${lakasId}`)
//             .then(response => {
//                 if (!response.ok) {
//                     throw new Error(`HTTP hiba: ${response.status} ${response.statusText}`);
//                 }
//                 return response.json();
//             })
//             .then(events => {
//                 if (events.error) {
//                     console.error("Hiba a válaszban:", events.error);
//                 } else {
//                     // Események hozzáadása a FullCalendarhoz
//                     calendar.addEventSource(events);
//                 }
//             })
//             .catch(error => {
//                 console.error("Hiba történt:", error);
//                 alert("Nem sikerült betölteni az eseményeket. Kérlek, próbáld újra később.");
//             });
//     } else {
//         console.error("Nincs lakas_id az URL-ben.");
//         alert("Nincs lakás azonosító megadva. Kérlek, ellenőrizd az URL-t.");
//     }
// });

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
        console.log("A lakasId az URL-ben:", lakasId); //Ellenőrzés
        fetch(`../php/naptar.php?lakas_id=${lakasId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP hiba: ${response.status} ${response.statusText}`);
                }
                return response.json();
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
                alert("Nem sikerült betölteni az eseményeket. Kérlek, próbáld újra később: " + error.message);
            });
    } else {
        console.error("Nincs lakas_id az URL-ben.");
        alert("Nincs lakás azonosító megadva. Kérlek, ellenőrizd az URL-t.");
    }
});