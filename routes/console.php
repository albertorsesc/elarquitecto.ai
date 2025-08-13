<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Newsletter processing with git hook integration
Artisan::command('hook:newsletter', function () {
    $this->call('newsletter:process', ['--adjust-time' => true]);
})->purpose('Process newsletter from git commit (use in git hooks)');

// Convenient alias for manual processing
Artisan::command('newsletter:auto', function () {
    $this->call('newsletter:process', ['--adjust-time' => true]);
})->purpose('Auto-process newsletters with time adjustment');
