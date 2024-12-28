document.addEventListener('DOMContentLoaded', function() {
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
                const foglalas = prompt('Írja be ki várható a lakásba? (vendég/takarító)');
                if (foglalas == "vendég") {
                    const mettolMeddig = prompt('Mettől meddig lesz lefoglalva?')
                    day.style.backgroundColor = 'red';
                }else if(foglalas == "takarító"){
                    day.style.backgroundColor = '#007bff';
                }else{
                    day.style.backgroundColor = '#e0e0e0';
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