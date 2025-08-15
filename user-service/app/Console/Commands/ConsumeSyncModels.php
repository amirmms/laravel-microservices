<?php

namespace App\Console\Commands;

use App\Enums\ActionType;
use App\Http\Services\RabbitMQService;
use App\Jobs\SyncUserByAuthService;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ConsumeSyncModels extends Command
{

    private string $cacheKey = 'is_consume';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume:sync-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle()
    {
        if (Cache::get($this->cacheKey, false) == true) {
            return;
        }

        Cache::rememberForever($this->cacheKey, function () {
            return true;
        });

        $mq = new RabbitMQService();

        $callback = function ($msg) {
            $data = json_decode($msg->body, true);

            if ($data['action_type'] == ActionType::Create->name || $data['action_type'] == ActionType::Update->name) {
                if ($data['class'] === User::class) {
                    SyncUserByAuthService::dispatch($data['user']);
                }
            } else {
                Log::error('RABBITMQ action type not found');
            }
        };

        $mq->consume($callback);

        Cache::forget($this->cacheKey);
    }
}
