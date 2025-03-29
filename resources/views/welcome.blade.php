<!DOCTYPE html>
<html>
<head>
    <title>Llama Chat - Local AI Assistant</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 0;
            color: #1a1a1a;
        }

        .header {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            padding: 4rem 2rem;
            text-align: center;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .feature-card h3 {
            color: #0ea5e9;
            margin-bottom: 1rem;
        }

        .chat-section {
            background: white;
            padding: 2rem;
            margin: 2rem auto;
            max-width: 1000px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .chat-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        #chatMessages {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: #f8fafc;
        }

        .message {
            padding: 15px 20px;
            margin: 10px 0;
            border-radius: 15px;
            max-width: 80%;
            position: relative;
            line-height: 1.8;
            white-space: pre-line;
        }

        .user {
            background: #0ea5e9;
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 5px;
        }

        .assistant {
            background: #f1f3f4;
            color: #1a1a1a;
            margin-right: auto;
            border-bottom-left-radius: 5px;
        }

        #messageForm {
            display: flex;
            gap: 10px;
            padding: 20px;
            background: white;
            border-top: 1px solid #eee;
        }

        #messageInput {
            flex: 1;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 25px;
            font-size: 16px;
            transition: all 0.3s;
        }

        #messageInput:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        button {
            padding: 15px 30px;
            background: #0ea5e9;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            background: #0284c7;
            transform: translateY(-1px);
        }

        .typing {
            padding: 20px;
            color: #666;
            font-style: italic;
            display: none;
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: #666;
            background: #f8fafc;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Llama Chat</h1>
        <p>Experience the power of local AI with Llama - Your personal AI assistant running directly on your machine.</p>
    </header>

    <div class="features">
        <div class="feature-card">
            <h3>Local Processing</h3>
            <p>All conversations are processed locally on your machine, ensuring complete privacy and data security.</p>
        </div>
        <div class="feature-card">
            <h3>Fast Response</h3>
            <p>Get quick responses without internet latency, powered by efficient local processing.</p>
        </div>
        <div class="feature-card">
            <h3>Memory Context</h3>
            <p>Maintains conversation context for more meaningful and coherent interactions.</p>
        </div>
    </div>

    <section class="chat-section">
        <div class="chat-container">
            <div id="chatMessages">
                @foreach($conversation as $message)
                    <div class="message {{ $message['role'] }}">
                        <strong>{{ ucfirst($message['role']) }}</strong>
                        {{ $message['content'] }}
                    </div>
                @endforeach
            </div>

            <div class="typing" id="typingIndicator">AI is thinking...</div>

            <form id="messageForm">
                <input type="text" id="messageInput" placeholder="Ask anything..." autocomplete="off">
                <button type="submit">Send</button>
            </form>
        </div>
    </section>

    <footer>
        <p>Powered by Llama - Local Large Language Model</p>
    </footer>

    <!-- Keep your existing JavaScript code here unchanged -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function scrollToBottom() {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        scrollToBottom();

        $('#messageForm').on('submit', function(e) {
            e.preventDefault();
            let message = $('#messageInput').val();
            if (!message.trim()) return;
            
            $('#messageInput').prop('disabled', true);
            $('#typingIndicator').show();
            
            // Clear existing messages and show full conversation
            $('#chatMessages').empty();
            
            $.ajax({
                url: '/chat',
                method: 'POST',
                data: { message: message },
                success: function(response) {
                    // Display entire conversation history
                    response.conversation.forEach(function(msg) {
                        // Format the content to preserve line breaks
                        const formattedContent = msg.content
                            .replace(/\. /g, '.\n') // Add line break after periods
                            .replace(/\* /g, '\n* ') // Add line break before bullets
                            .replace(/\d\. /g, '\n$&'); // Add line break before numbered points
                            
                        $('#chatMessages').append(`
                            <div class="message ${msg.role}">
                                <strong>${msg.role.charAt(0).toUpperCase() + msg.role.slice(1)}</strong>
                                ${formattedContent}
                            </div>
                        `);
                    });
                    
                    $('#messageInput').val('').prop('disabled', false).focus();
                    $('#typingIndicator').hide();
                    scrollToBottom();
                },
                error: function() {
                    $('#messageInput').prop('disabled', false);
                    $('#typingIndicator').hide();
                    alert('Error sending message. Please try again.');
                }
            });
        });
    </script>
</body>
</html>