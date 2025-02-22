<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\View\View;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    public function showChat(): View
    {
        return view('testing.chat');
    }

    public function sendMessage(Request $request): View
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->message,
                    ],
                ],
            ]);

            $message = $response->choices[0]->message->content ?? 'Sorry, no response.';
        } catch (\Exception $e) {
            $message = 'Error: ' . $e->getMessage();
        }

        return view('testing.chat', [
            'response' => $message,
        ]);
    }
}
