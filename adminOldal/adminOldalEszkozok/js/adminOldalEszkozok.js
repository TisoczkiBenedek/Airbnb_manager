document.addEventListener("DOMContentLoaded", function() {
    const felvitel = document.getElementById("felvitel");
    const eszkozFelvitel = document.getElementById("eszkozFelvitele");
    const span = document.getElementsByClassName("close")[0];
    const form = document.getElementById("popupForm");

    eszkozFelvitel.onclick = function() {
        felvitel.style.display = "block";
    }

    span.onclick = function() {
        felvitel.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == felvitel) {
            felvitel.style.display = "none";
        }
    }

    if (form) {
        form.onsubmit = function() {
            setTimeout(() => {
                form.reset();
                felvitel.style.display = "none";
            }, 100);
        }
    }
});
