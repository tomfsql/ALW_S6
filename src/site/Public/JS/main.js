/**
 * main.js - Connecté à l'API
 */

document.addEventListener("DOMContentLoaded", () => {
    // URL vers le webservice (Assurez-vous que le port est le bon)
    const API_URL = "http://localhost:50181/";

    // Récupération dynamique du nom d'utilisateur depuis la balise <body>
    const username = document.body.dataset.username || "bean"; 

    const engine = new FermeEngine();

    // ---------------------------------------------------------
    // 1. CHARGEMENT DE L'ÉTAT (Inventaire et Bâtiments)
    // ---------------------------------------------------------
    engine.onLoadState(() => {
        console.log(`Rafraîchissement de l'état global du jeu pour ${username}`);

        // On appelle l'API pour récupérer la sauvegarde du joueur
        // (Ajustez la route selon votre API, ex: API_URL + 'save?user=' + username)
        return fetch(`${API_URL}save?username=${username}`)
            .then(response => {
                if (!response.ok) throw new Error("Erreur réseau");
                return response.json();
            })
            .then(data => {
                // Mise à jour de l'INVENTAIRE
                // On suppose que l'API renvoie { inventory: { ble: 10, eau: 5 ... } }
                if (data.inventory) {
                    for (const [resourceName, amount] of Object.entries(data.inventory)) {
                        const stockOutput = document.querySelector(`#product-${resourceName} .stock`);
                        if (stockOutput) {
                            stockOutput.textContent = amount;
                        }
                    }
                }

                // Mise à jour des BÂTIMENTS
                // On suppose que l'API renvoie { buildings: { champ_ble: { level: 2 } } }
                if (data.buildings) {
                    for (const [buildingId, buildingData] of Object.entries(data.buildings)) {
                        const levelOutput = document.querySelector(`#building-${buildingId} .level`);
                        if (levelOutput) {
                            levelOutput.textContent = buildingData.level;
                        }
                    }
                }
            })
            .catch(error => console.error("Erreur lors du chargement de l'état:", error));
    });

    // ---------------------------------------------------------
    // 2. ACTION : RÉCOLTER
    // ---------------------------------------------------------
    engine.onHarvest((buildingId) => {
        console.log(`Récolte demandée sur ${buildingId}`);
        
        // Appel POST vers l'API pour récolter
        return fetch(`${API_URL}harvest`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            // On envoie le nom du joueur et le bâtiment concerné
            body: JSON.stringify({
                username: username,
                building: buildingId
            })
        })
        .then(response => {
            if (!response.ok) throw new Error("La récolte a échoué (ressources insuffisantes ou erreur serveur)");
            return response.json();
        });
    });

    // ---------------------------------------------------------
    // 3. ACTION : AMÉLIORER
    // ---------------------------------------------------------
    engine.onUpgrade((buildingId) => {
        console.log(`Amélioration demandée sur ${buildingId}`);

        // Appel POST vers l'API pour améliorer
        return fetch(`${API_URL}upgrade`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                building: buildingId
            })
        })
        .then(response => {
            if (!response.ok) throw new Error("L'amélioration a échoué (fonds insuffisants ou erreur serveur)");
            return response.json();
        });
    });

    // Démarrage du moteur
    engine.init();
});