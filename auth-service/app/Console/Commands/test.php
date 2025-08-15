<?php

namespace App\Console\Commands;

use App\Http\Services\RabbitMQService;
use App\Jobs\TestRabbitJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

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
        $count = 0;
        $s = new RabbitMQService();

       do{
           $count++;

           $s->producer([
               'count'=>$count
           ]);

           sleep(1);
       }while(true);

    }
}
