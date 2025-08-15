<?php

namespace App\Jobs;

use App\Enums\ActionType;
use App\Http\Services\RabbitMQService;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncUser implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
         public User $user,
         public ActionType $actionType,
    )
    {}

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(RabbitMQService $mq): void
    {
        $payload = [
            'action_type' => $this->actionType->name,
            'user' => $this->user,
        ];

        $mq->producer($payload);
    }
}
