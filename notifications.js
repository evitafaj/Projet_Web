document.addEventListener('DOMContentLoaded', () => {
    const notificationsList = document.getElementById('notifications-list');

    // Exemple de notifications (elles peuvent être remplacées par des données dynamiques)
    const notifications = [
        {
            type: "Conférence",
            details: "Conférence sur l'intelligence artificielle organisée par OMNES Education le 15 décembre 2024 à 18h.",
        },
        {
            type: "Réseau",
            details: "Votre ami Thomas Edison a décroché une bourse d'excellence pour ses études en ingénierie.",
        },
        {
            type: "Réseau",
            details: "Henry Ford a été embauché chez McLaren en tant qu'ingénieur logiciel.",
        }
    ];

    // Ajouter les notifications à la liste
    notifications.forEach(notification => {
        const li = document.createElement('li');
        li.innerHTML = `<strong>${notification.type}:</strong> ${notification.details}`;
        notificationsList.appendChild(li);
    });
});
