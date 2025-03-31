# Llama Chat - Local AI Assistant

A web-based chat interface for interacting with the Llama language model locally. This application provides a user-friendly way to communicate with Llama while maintaining conversation context and ensuring data privacy through local processing.

## Features

- **Local Processing**: All conversations are processed locally on your machine
- **Conversation Memory**: Maintains context throughout the chat session
- **Real-time Responses**: Fast response times with local model processing
- **User-friendly Interface**: Clean and intuitive chat interface
- **Secure**: No data sent to external servers

## Prerequisites

- PHP >= 8.1
- Laravel Framework
- Composer
- Ollama

## Complete Setup Guide

### 1. Install Ollama

```bash
curl https://ollama.ai/install.sh | sh
```

- Llama.cpp installed locally
- Node.js and NPM

## Installation

1. Clone the repository:
```bash
git clone <your-repository-url>
cd <repository-name>
```
## Download Llama Model

# Pull the Llama model
```
ollama pull llama3.2
```

# Verify the model is downloaded
```
ollama list
```

## Start Ollama Server

- The Ollama server starts automatically after installation
- Verify it's running at http://127.0.0.1:11434

## Setup Laravel Project

# Install PHP dependencies
```
composer install
```

# Copy environment file
```
cp .env.example .env
```

# Generate application key
```
php artisan key:generate
```

# Start Laravel development server
```
php artisan serve
```

## Usage

- Ensure Ollama server is running (port 11434)
- Visit http://127.0.0.1:8000 in your browser
- Start chatting with the AI

## API Endpoints

- GET / : Main chat interface
- POST /chat : Send messages to Llama

## Configuration
### Environment Variables

```
APP_URL=http://127.0.0.1:8000
OLLAMA_SERVER_URL=http://127.0.0.1:11434
```

## Troubleshooting

If Ollama server isn't responding:

- Check if Ollama is running: ps aux | grep ollama
- Restart Ollama: sudo pkill ollama && ollama serve
- Verify API is accessible: curl http://127.0.0.1:11434/api/tags

If chat doesn't respond:

- Confirm Ollama server is running
- Check Laravel logs: storage/logs/laravel.log
- Verify API endpoint in browser console
- Ensure model is downloaded: ollama list

Common Model Issues:

- If model is stuck: ollama rm llama3.2 && ollama pull llama3.2
- For memory issues: Restart Ollama server

## Contributing
- Fork the repository
- Create your feature branch ( git checkout -b feature/AmazingFeature )
- Commit your changes ( git commit -m 'Add some AmazingFeature' )
- Push to the branch ( git push origin feature/AmazingFeature )
- Open a Pull Request

## License
This project is licensed under the MIT License - see the LICENSE file for details.