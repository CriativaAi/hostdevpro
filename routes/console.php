<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Régua diária de cobrança amigável e lembretes de PIX/Fatura via WhatsApp
Schedule::command('invoices:send-reminders')->dailyAt('09:00');
