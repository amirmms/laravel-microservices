<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncUserByAuthService implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $user,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::query()
            ->updateOrCreate([
                'email' => $this->user['email'],
            ], [
                ...$this->user,
                'password' => 'secret',
            ]);
    }
}
