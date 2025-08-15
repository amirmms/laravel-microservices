<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TestRabbitJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $message)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        dump("RabbitMQ message received: " . $this->message);
    }
}
