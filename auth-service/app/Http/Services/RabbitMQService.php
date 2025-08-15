<?php

namespace App\Http\Services;

use Exception;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private AMQPStreamConnection $connection;
    private AbstractChannel|AMQPChannel $channel;
    private string $queue;

    /**
     * @throws Exception
     */
    public function __construct(string $queue = '')
    {
        $this->queue = $queue != '' ?: config('rabbitmq.queue');

        $this->connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password')
        );

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @throws Exception
     */
    public function producer(array $data): void
    {
        $msg = new AMQPMessage(json_encode($data), ['delivery_mode' => 2]);

        $this->channel->basic_publish($msg, '', $this->queue);
    }

    /**
     * @throws Exception
     */
    public function consume($callback, int $ttl = 55): void
    {
        $this->channel->basic_consume($this->queue, '', false, true, false, false, $callback);

        $start = microtime(true);
        while ($this->channel->is_consuming() && (microtime(true) - $start) < $ttl) {
            $this->channel->wait();
        }
    }
}
