<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;          // model
use App\Events\MessageSent;      // event


class ChatController extends Controller
{

    public function index()
    {

        return view(

            'chat.index'

        );

    }
    public function send(Request $request)
    {

        $message = Message::create([

            'sender_id' => auth()->id(),

            'receiver_id' => $request->receiver_id,

            'message' => $request->message,

            'status' => 'sent'

        ]);


        broadcast(

            new MessageSent(

                $message

            )

        )->toOthers();



        return response()->json(

            $message

        );

    }

}