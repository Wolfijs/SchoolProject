<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LobbyController;
use App\Http\Controllers\LobbyJoinController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController; // Ensure this is imported
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

        Route::get('/chat', [MessageController::class, 'index'])->name('chat'); // Load chat view
        Route::post('/chat/send', [MessageController::class, 'sendMessage'])->name('chat.send');
        Route::get('/chat/messages', [MessageController::class, 'loadMessages'])->name('chat.messages'); // Load messages
 
    
});

Route::get('/events', function () {
    return view('events');
})->name('events');

Auth::routes(['verify' => true]);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
