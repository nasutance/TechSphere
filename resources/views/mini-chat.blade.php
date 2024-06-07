<x-layouts.base :title="'Mini-Chat'">
  <!-- Utilise le layout de base avec le titre 'Mini-Chat' -->

  <div></div>
  <!-- Un div vide pour espacement ou structure -->

  <div class="chat-container-wrapper">
    <!-- Conteneur principal pour centrer le chat -->
    <div class="chat-container">
      <!-- Conteneur du chat -->

      <form action="{{ route('chat.store') }}" method="POST">
        <!-- Formulaire pour envoyer un message, envoyé en méthode POST -->
        @csrf
        <!-- Protection CSRF -->
        <textarea name="message" placeholder="Écrivez votre message..." required></textarea>
        <!-- Champ de texte pour écrire le message avec un placeholder -->
        <button type="submit">Envoyer</button>
        <!-- Bouton de soumission pour envoyer le message -->
      </form>

      <div class="messages" id="messages">
        <!-- Conteneur pour afficher les messages -->
        @foreach($messages as $message)
        <!-- Boucle pour afficher chaque message -->
        <div class="message">
          <!-- Conteneur pour un message -->
          <strong>{{ $message->username }}:</strong> {{ $message->message }}
          <!-- Affiche le nom d'utilisateur en gras et le message -->
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <style>
    .chat-container-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      /* Centre le conteneur du chat horizontalement et verticalement */
    }

    .chat-container {
      max-width: 800px;
      width: 100%;
      /* S'assure que le conteneur prend toute la largeur */
      padding: 20px;
      background-color: #f9f9f9;
      opacity: 0.8;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      /* Style du conteneur du chat : fond, ombre, arrondi des coins */
    }

    .chat-container h2 {
      text-align: center;
      margin-bottom: 20px;
      /* Style pour le titre du chat : centré et marge en bas */
    }

    .chat-container form {
      display: flex;
      flex-direction: column;
      /* Formulaire en colonne */
    }

    .chat-container textarea {
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      /* Style du champ de texte : padding, marge, bordure, arrondi des coins */
    }

    .chat-container button {
      padding: 10px;
      border: none;
      background-color: #007bff;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      /* Style du bouton : padding, couleur de fond, couleur du texte, arrondi des coins, curseur pointeur */
    }

    .chat-container button:hover {
      background-color: #0056b3;
      /* Style du bouton au survol : couleur de fond plus foncée */
    }

    .messages {
      margin-top: 20px;
      /* Marge en haut pour le conteneur des messages */
    }

    .message {
      padding: 10px;
      border-bottom: 1px solid #eee;
      /* Style de chaque message : padding et bordure en bas */
    }

    .message strong {
      color: #333;
      /* Couleur du texte en gras pour le nom d'utilisateur */
    }
  </style>
  <!-- Styles CSS pour le chat -->

  <script>
    // Fonction pour récupérer les nouveaux messages via AJAX
    function fetchMessages() {
      fetch('{{ route('chat.fetchMessages') }}')
        .then(response => response.json())
        .then(data => {
          const messagesContainer = document.getElementById('messages');
          messagesContainer.innerHTML = '';
          data.forEach(message => {
            const messageElement = document.createElement('div');
            messageElement.classList.add('message');
            messageElement.innerHTML = `<strong>${message.username}:</strong> ${message.message}`;
            messagesContainer.appendChild(messageElement);
          });
        });
    }

    // Rafraîchit les messages toutes les 5 secondes
    setInterval(fetchMessages, 5000);

    // Appelle fetchMessages immédiatement pour charger les messages au démarrage
    fetchMessages();
  </script>
</x-layouts.base>
