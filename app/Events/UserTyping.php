<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;


class UserTyping implements ShouldBroadcast
{

use Dispatchable,
InteractsWithSockets,
SerializesModels;


public $receiverId;
public $senderId;



public function __construct(

$receiverId,

$senderId

){

$this->receiverId=

$receiverId;

$this->senderId=

$senderId;

}



public function broadcastOn(): array
{

return [

new PrivateChannel(

'chat.' .

$this->receiverId

)

];

}



public function broadcastAs(): string
{

return 'typing';

}



public function broadcastWith(): array
{

return [

'sender_id'=>

$this->senderId

];

}


}