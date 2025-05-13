<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LobbyController;
use App\Http\Controllers\LobbyJoinController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController; // Ensure this is imported
use App\Http\Controllers\LobbyChatController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/signup', [RegisterController::class, 'showRegistrationForm'])->name('signup');
    Route::post('/signup', [RegisterController::class, 'register'])->name('register');
});

Route::get('/fyt', [LobbyController::class, 'index'])->name('fyt');

Route::middleware('auth')->group(function () {
    Route::post('/lobby/store', [LobbyController::class, 'store'])->name('lobby.store');
    Route::post('/lobby/{lobby}/join', [LobbyJoinController::class, 'join'])->name('lobby.join');
    Route::delete('/lobby/{lobby}', [LobbyController::class, 'destroy'])->name('lobby.destroy');
    
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    /* Lobbies CRUD / membership --------------------------------------- */
    Route::post  ('/lobby/store',          [LobbyController::class, 'store'  ])->name('lobby.store');
    Route::get   ('/lobby/{lobby}',        [LobbyController::class, 'show'   ])->name('lobby.show');   // unique lobby page
    Route::post  ('/lobby/{lobby}/join',   [LobbyController::class, 'join'   ])->name('lobby.join');
    Route::post  ('/lobby/{lobby}/leave',  [LobbyController::class, 'leave'  ])->name('lobby.leave');
    Route::delete('/lobby/{lobby}',        [LobbyController::class, 'destroy'])->name('lobby.destroy');
    
    /* Lobby Chat Routes */
    Route::post('/lobby/{lobby}/chat', [LobbyChatController::class, 'send'])->name('lobby.chat.send')->middleware('auth');

    Route::get('/chat', [MessageController::class, 'index'])->name('chat'); // Load chat view
    Route::post('/chat/send', [MessageController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/messages', [MessageController::class, 'loadMessages'])->name('chat.messages'); // Load messages

    // Tournament routes
    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
    Route::post('/tournaments/{tournament}/join', [TournamentController::class, 'join'])->name('tournaments.join');
    Route::post('/tournaments/{tournament}/leave', [TournamentController::class, 'leave'])->name('tournaments.leave');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
});

Route::get('/events', function () {
    return view('events');
})->name('events');

Auth::routes(['verify' => true]);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
