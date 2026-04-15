<?php

use Illuminate\Foundation\Inspiring;
use App\Console\Commands\CheckPowerStatus;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('check:power', function () {
    $this->call(CheckPowerStatus::class);
})->purpose('Check power status of devices and update their online status');
