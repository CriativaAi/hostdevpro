<?php

use App\Http\Controllers\AiSiteBuilderController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HostingAccountController;
use App\Http\Controllers\InvoiceController;
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
Route::view('/termos/privacidade', 'terms.privacy')->name('terms.privacy');

// Monitoramento de Status dos Sistemas em Tempo Real
Route::view('/status', 'status')->name('status');

// Área do Cliente / Dashboard Dinâmico
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Clientes & Projetos
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);

    // Servidores & Hospedagem
    Route::resource('servers', ServerController::class);
    Route::patch('hosting/{hosting}/toggle-status', [HostingAccountController::class, 'toggleStatus'])->name('hosting.toggle-status');
    Route::resource('hosting', HostingAccountController::class);

    // Faturamento & Invoices
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('invoices/{invoice}/stripe', [InvoiceController::class, 'payStripe'])->name('invoices.pay-stripe');
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');

    // Central de Chamados & Suporte
    Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::resource('tickets', TicketController::class);

    // Programa de Afiliados & Indicações
    Route::get('affiliates', [AffiliateController::class, 'index'])->name('affiliates.index');
    Route::post('affiliates/activate', [AffiliateController::class, 'activate'])->name('affiliates.activate');
    Route::post('affiliates/withdraw', [AffiliateController::class, 'withdraw'])->name('affiliates.withdraw');
    Route::put('affiliates/pix', [AffiliateController::class, 'updatePix'])->name('affiliates.update-pix');


    // Criador de Sites Instantâneo com IA Gemini (HDP AI Site Builder)
    Route::prefix('ai-builder')->name('ai-builder.')->group(function () {
        Route::get('/', [AiSiteBuilderController::class, 'index'])->name('index');
        Route::get('/create', [AiSiteBuilderController::class, 'create'])->name('create');
        Route::post('/', [AiSiteBuilderController::class, 'store'])->name('store');
        Route::get('/{aiSite}/studio', [AiSiteBuilderController::class, 'studio'])->name('studio');
        Route::post('/{aiSite}/refine', [AiSiteBuilderController::class, 'refine'])->name('refine');
        Route::get('/{aiSite}/preview', [AiSiteBuilderController::class, 'preview'])->name('preview');
        Route::post('/{aiSite}/publish', [AiSiteBuilderController::class, 'publish'])->name('publish');
        Route::get('/{aiSite}/download/html', [AiSiteBuilderController::class, 'downloadHtml'])->name('download.html');
        Route::get('/{aiSite}/download/zip', [AiSiteBuilderController::class, 'downloadZip'])->name('download.zip');
        Route::delete('/{aiSite}', [AiSiteBuilderController::class, 'destroy'])->name('destroy');
    });

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
