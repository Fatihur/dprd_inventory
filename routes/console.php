<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal kirim notifikasi jatuh tempo setiap hari jam 08:00
Schedule::command('peminjaman:kirim-notifikasi-jatuh-tempo')->dailyAt('08:00');
