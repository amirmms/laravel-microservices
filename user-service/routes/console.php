<?php

use App\Console\Commands\ConsumeSyncModels;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ConsumeSyncModels::class)
    ->everyMinute();
