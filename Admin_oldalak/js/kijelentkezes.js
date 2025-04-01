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