<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un assistant utile.'],
                ['role' => 'user', 'content' => $request->input('message')],
            ],
        ]);

        return response()->json([
            'reply' => $response->choices[0]->message->content,
        ]);
    }
}

