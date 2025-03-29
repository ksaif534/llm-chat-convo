<?php

use Illuminate\Support\Facades\{Route,Http};

Route::get('/', function () {
    // Initialize empty conversation if not exists
    if (!session()->has('conversation')) {
        session()->put('conversation', []);
    }
    
    return view('welcome', [
        'conversation' => session()->get('conversation')
    ]);
});

Route::post('/chat', function () {
    $conversation = session()->get('conversation', []);
    $newPrompt = request('message');
    
    // Add new user message
    $conversation[] = ['role' => 'user', 'content' => $newPrompt];
    
    // Build context from entire conversation history
    $contextPrompt = "";
    foreach ($conversation as $message) {
        $contextPrompt .= "{$message['role']}: {$message['content']}\n";
    }
    
    $response = Http::post('http://127.0.0.1:11434/api/generate', [
        "model" => "llama3.2:latest",
        "prompt" => $contextPrompt,
        "stream" => false 
    ])->json();

    $data = $response['response'];
    $conversation[] = ['role' => 'assistant', 'content' => $data];
    
    session()->put('conversation', $conversation);

    return response()->json([
        'response' => $data,
        'conversation' => $conversation
    ]);
});
