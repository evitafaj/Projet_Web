document.addEventListener('DOMContentLoaded', () => {
    const contactList = document.getElementById('contact-list');
    const chatHistory = document.getElementById('chat-history');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const currentContact = document.getElementById('current-contact');

    // Exemple de contacts avec historique des messages
    const contacts = {
        "Charles Schwab": [
            { sender: "Charles Schwab", text: "Salut ! Comment ça va ?" },
            { sender: "Vous", text: "Très bien, merci ! Et toi ?" }
        ],
        "Henry Ford": [
            { sender: "Henry Ford", text: "As-tu vu la dernière conférence ?" },
            { sender: "Vous", text: "Oui, c'était génial !" }
        ],
        "Thomas Edison": [] // Pas encore de messages
    };

    let activeContact = null; // Contact actuellement sélectionné

    // Fonction pour afficher la liste des contacts
    function renderContactList() {
        contactList.innerHTML = '';
        for (const contact in contacts) {
            const li = document.createElement('li');
            li.textContent = contact;
            li.classList.add('contact');
            li.addEventListener('click', () => selectContact(contact));
            contactList.appendChild(li);
        }
    }

    // Fonction pour sélectionner un contact
    function selectContact(contactName) {
        activeContact = contactName;
        currentContact.textContent = `Chat avec ${contactName}`;
        renderChatHistory();
        chatForm.style.display = 'block';
    }

    // Fonction pour afficher l'historique des messages
    function renderChatHistory() {
        chatHistory.innerHTML = '';
        if (activeContact) {
            contacts[activeContact].forEach(message => {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message');
                messageDiv.innerHTML = `<strong>${message.sender}:</strong> ${message.text}`;
                chatHistory.appendChild(messageDiv);
            });
            chatHistory.scrollTop = chatHistory.scrollHeight; // Scroller vers le bas
        }
    }

    // Fonction pour envoyer un nouveau message
    chatForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const newMessage = chatInput.value.trim();
        if (newMessage && activeContact) {
            contacts[activeContact].push({ sender: "Vous", text: newMessage });
            renderChatHistory();
            chatInput.value = '';
        }
    });

    // Initialisation
    renderContactList();
});
