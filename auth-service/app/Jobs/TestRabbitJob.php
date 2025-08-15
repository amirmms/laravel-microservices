<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TestRabbitJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public $message)
    {
    }

    public function handle()
    {
        \Log::info("RabbitMQ message send: " . $this->message);
    }
}
