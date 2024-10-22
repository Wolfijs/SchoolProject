<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent; 

class MessageController extends Controller
{
    /**
     * Display the chat view with all messages.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Load messages with user relationship and order by latest
        $messages = Message::with('user')->latest()->get();
        return view('chat', compact('messages'));
    }

    /**
     * Handle the sending of a message.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'message' => 'required|string|max:255',
            ]);
    
            $user = Auth::user();
            if (!$user) {
                throw new \Exception('User not authenticated.');
            }
    
            // Create and save the message
            $message = new Message();
            $message->content = $validated['message'];
            $message->user_id = $user->id;
            $message->username = $user->name; // Ensure this is not null
            $message->save(); // Attempt to save the message in the database
    
            // Broadcast the message to others
            broadcast(new MessageSent($message))->toOthers();
    
            return response()->json(['message' => $message], 201);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error sending message: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send message.', 'message' => $e->getMessage()], 500);
        }
    }
}
