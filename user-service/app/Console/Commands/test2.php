<?php

namespace App\Console\Commands;

use App\Enums\ActionType;
use App\Http\Services\RabbitMQService;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class test2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test2';

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
        $s = new RabbitMQService();

        $callback = function ($msg) {
            $data = json_decode($msg->body, true);

            if($data['action_type'] == ActionType::Create->name || $data['action_type'] == ActionType::Update->name){
                User::query()
                    ->updateOrCreate([
                        'email' => $data['user']['email'],
                    ],[
                        ...$data['user'],
                        'password' => 'secret',
                    ]);
            }else{
                Log::error('RABBITMQ action type not found');
            }
        };

        $s->consume($callback);
    }
}
