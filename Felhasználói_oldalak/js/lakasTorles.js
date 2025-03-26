document.getElementById("torlesNem").addEventListener("click", () => {
    document.getElementById("torlesModal").style.display = "none";
  });
  
  document.getElementById("torlesIgen").addEventListener("click", async () => {
    document.getElementById("torlesModal").style.display = "none";
    if(selectedLakasId) {
      await lakasTorles(selectedLakasId);
      selectedLakasId = null;
    }
  });

async function lakasTorles(lakasId) {
    try {
        const response = await fetch("../php/lakasTorles.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ lakasId: lakasId }),
        });

        // Ellenőrizzük, hogy a válasz JSON-e
        const rawResponse = await response.text();
        let result;
        try {
            result = JSON.parse(rawResponse);
        } catch (error) {
            console.error("A szerver nem JSON választ küldött:", rawResponse);
            throw new Error("A szerver hibás választ küldött.");
        }

        if (result.success) {
            showToast(result.success, "success");
            setTimeout(() => {
                location.reload(); // Oldal frissítése
            }, 300);
        } else {
            // Hiba esetén hibaüzenet megjelenítése
            showToast(result.error, "error");
        }
    } catch (error) {
        console.error("Hiba történt a lakás törlése során:", error);
        showToast("Hiba történt a lakás törlése során.", "error");
    }
}

// Toast üzenet megjelenítése
function showToast(message, type) {
    const toastLiveExample = document.getElementById("liveToast");
    const toastBody = document.getElementById("toast-body");
    const toast = new bootstrap.Toast(toastLiveExample);

    toastLiveExample.classList.remove("error", "success");
    toastLiveExample.classList.add(type === "error" ? "error" : "success");

    toastBody.textContent = message;
    toast.show();
}

// Exportáljuk a függvényt, hogy más fájlokból is elérhető legyen
window.lakasTorles = lakasTorles;