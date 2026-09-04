<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\HostingAccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Contratos e Termos Oficiais
Route::view('/termos/contrato-vps', 'terms.vps')->name('terms.vps');
Route::view('/termos/contrato-hospedagem', 'terms.hosting')->name('terms.hosting');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('servers', ServerController::class);
    Route::patch('hosting/{hosting}/toggle-status', [HostingAccountController::class, 'toggleStatus'])->name('hosting.toggle-status');
    Route::resource('hosting', HostingAccountController::class);
    Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::resource('tickets', TicketController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
