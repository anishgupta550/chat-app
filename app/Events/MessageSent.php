<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Broadcast channel
     */
    public function broadcastOn(): array
    {
        return [

            new PrivateChannel(

                'chat.' .

                $this->message->receiver_id

            )

        ];
    }

    /**
     * Event name (optional but recommended)
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }


    /**
     * Data send to frontend
     */
    public function broadcastWith(): array
    {

        return [

            'id' => $this->message->id,

            'message' => $this->message->message,

            'sender_id' => $this->message->sender_id,

            'receiver_id' => $this->message->receiver_id,

            'status' => $this->message->status,

            'created_at' => $this->message->created_at->format('H:i')

        ];

    }
}