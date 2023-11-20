<?php

use App\Livewire\Conversation;
use App\Livewire\Dashboard;
use App\Livewire\Login;
use App\Livewire\Message;
use App\Livewire\Registraion;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', Login::class)->name('login');
Route::get('/registration', Registraion::class)->name('registration');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/conversation', Conversation::class)->name('conversation');
    Route::get('/message/{conversationId}', Message::class)->name('message');
});
