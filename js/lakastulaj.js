document.addEventListener('DOMContentLoaded', function() {
    const week = document.getElementById('week');
    const calendarTitle = document.getElementById('calendar-title');
    const calendar = document.getElementById('calendar');
    const prevMonthButton = document.getElementById('prev-month');
    const nextMonthButton = document.getElementById('next-month');
    
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    
    function renderCalendar(month, year) {
        calendar.innerHTML = '';
        calendarTitle.textContent = `${year} ${new Intl.DateTimeFormat('hu', { month: 'long' }).format(new Date(year, month))}`;
        
        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        week.innerHTML = `  <div class="daysOfWeek">Hétfő</div>
                                <div class="daysOfWeek">Kedd</div>
                                <div class="daysOfWeek">Szerda</div>
                                <div class="daysOfWeek">Csütörtök</div>
                                <div class="daysOfWeek">Péntek</div>
                                <div class="daysOfWeek">Szombat</div>
                                <div class="daysOfWeek">Vasárnap</div>`


        for (let i = 0; i < firstDayOfMonth; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'day';
            calendar.appendChild(emptyDay);
        }
        
        for (let i = 1; i <= daysInMonth; i++) {
            const day = document.createElement('div');
            day.className = 'day';
            day.textContent = i;
            day.addEventListener('click', () => {
                const computedStyle = window.getComputedStyle(day);
                const bgColor = computedStyle.backgroundColor;
                
                if (bgColor === 'rgb(224, 224, 224)') {
                    const foglalas = prompt('Írja be ki várható a lakásba? (vendég/takarító)');
                    if (foglalas == "vendég") {
        
                        const mettol = i;
                        const meddig = parseInt(prompt('Írja be az utolsó napot:'))
        
                        if (isNaN(meddig) || meddig > daysInMonth) { 
                            alert('Érvénytelen dátumokat adott meg.'); 
                        } else {
                            for (let j = mettol; j <= meddig; j++) {
                                const atSzinez = calendar.querySelector(`.day:nth-child(${j + firstDayOfMonth})`)
                                if(atSzinez) {
                                    atSzinez.style.backgroundColor = 'rgb(172, 62, 62)';
                                }
                            }
                        }
        
                    } else if (foglalas == "takarító") {
                        day.style.backgroundColor = '#007bff';
                    }
                } else {
                    if (bgColor === 'rgb(0, 123, 255)') {
                        day.style.backgroundColor = 'rgb(224, 224, 224)';
                    } else {
                        const modositasKezd = i;
                        const modositasVeg = parseInt(prompt('Meddig módosították a foglalást?'));
        
                        if (isNaN(modositasVeg) || modositasVeg > daysInMonth || modositasKezd > modositasVeg) { 
                            alert('Érvénytelen dátumokat adott meg.'); 
                        } else {
                            for (let k = modositasKezd; k <= modositasVeg; k++) {
                                const atSzinez = calendar.querySelector(`.day:nth-child(${k + firstDayOfMonth})`);
                                if (atSzinez) {
                                    atSzinez.style.backgroundColor = 'rgb(224, 224, 224)';
                                }
                            }
                        }
                    }
                }
            });
            calendar.appendChild(day);
        }
        
    }
    
    prevMonthButton.addEventListener('click', () => {
        currentMonth = (currentMonth - 1 + 12) % 12;
        if (currentMonth === 11) {
            currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
    });
    
    nextMonthButton.addEventListener('click', () => {
        currentMonth = (currentMonth + 1) % 12;
        if (currentMonth === 0) {
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    });
    
    renderCalendar(currentMonth, currentYear);
});

function removeEvent(element) {
    element.parentElement.remove();
}