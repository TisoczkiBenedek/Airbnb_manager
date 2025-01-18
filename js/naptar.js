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
        
        const firstDayOfMonth = (new Date(year, month, 1).getDay() + 6) % 7;
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
                const modal = document.getElementById("myModal");
                const span = document.getElementsByClassName("close")[0];
                
                fun

                modal.style.display = "block";
    
                span.onclick = function() {
                    modal.style.display = "none";
                }
                
                window.addEventListener("click", (event) => {
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                });
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

//modal
/*
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("myModal");
    const span = document.getElementsByClassName("close")[0];
    const openModal = document.getElementsByClassName("day")

    openModal.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none"
    }
    
    window.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      }
})
      */