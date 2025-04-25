<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * The data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => [
                'content' => $this->message->content,
                'username' => $this->message->user->name,
                'user_photo' => $this->message->user->photo ? asset('storage/' . $this->message->user->photo) : null,
                'user_id' => $this->message->user->id,
                'created_at' => $this->message->created_at->toIso8601String(),
            ]
        ];
    }

    public function broadcastOn()
    {
        return new Channel('chat');
    }
}
