<x-layouts.base :title="'Mini-Chat'">
  <div class="chat-container-wrapper">
    <div class="chat-container">
      <form action="{{ route('chat.store') }}" method="POST">
        @csrf
        <textarea name="message" maxlength="255" placeholder="Écrivez votre message..." required></textarea>
        <button type="submit">Envoyer</button>
      </form>

      <div class="messages" id="messages">
        @foreach($messages as $message)
        <div class="message">
          <strong>{{ $message->username }}:</strong> {{ $message->message }}
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
    }

    .chat-container {
      max-width: 800px;
      width: 100%;
      padding: 20px;
      background-color: #f9f9f9;
      opacity: 0.8;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .chat-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .chat-container form {
      display: flex;
      flex-direction: column;
    }

    .chat-container textarea {
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .chat-container button {
      padding: 10px;
      border: none;
      background-color: #007bff;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }

    .chat-container button:hover {
      background-color: #0056b3;
    }

    .messages {
      margin-top: 20px;
    }

    .message {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }

    .message strong {
      color: #333;
    }
  </style>

  <script>
    // Rafraîchit la liste des messages via AJAX
    function fetchMessages() {
      fetch('{{ route('chat.fetchMessages') }}')
        .then(response => response.json())
        .then(data => {
          const messagesContainer = document.getElementById('messages');
          messagesContainer.innerHTML = '';
          data.forEach(message => {
            const messageElement = document.createElement('div');
            messageElement.classList.add('message');

            const author = document.createElement('strong');
            author.textContent = message.username + ':';
            messageElement.appendChild(author);
            messageElement.appendChild(document.createTextNode(' ' + message.message));

            messagesContainer.appendChild(messageElement);
          });
        });
    }

    setInterval(fetchMessages, 5000);
    fetchMessages();
  </script>
</x-layouts.base>
