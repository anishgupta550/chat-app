<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(

    'auth'

)->group(

        function () {

            Route::get(

                '/chat',

                [ChatController::class, 'index']

            );


            Route::post(

                '/send',

                [ChatController::class, 'send']

            );

        }
    );


use App\Events\UserTyping;
use Illuminate\Http\Request;



Route::post(

    '/typing',

    function (Request $request) {


        broadcast(

            new UserTyping(

                $request->receiver_id,

                auth()->id()

            )

        )->toOthers();



        return response()

            ->json(

                true

            );


    }
);
require __DIR__ . '/auth.php';
