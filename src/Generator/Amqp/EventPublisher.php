<?php

namespace Bothelp\Generator\Amqp;

use Bothelp\Event\Event;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class EventPublisher
 */
final class EventPublisher implements \Bothelp\Generator\EventPublisher
{
    private const EXCHANGE_NAME = 'bothelp_events';

    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * @var
     */
    private $handlerAmount;

    /**
     * AmqpQueue constructor.
     * @param array $connectionParams
     * @param int $handlerAmount
     */
    public function __construct(array $connectionParams, int $handlerAmount)
    {
        $this->connection = new AMQPStreamConnection(
            $connectionParams['host'] ?? 'localhost',
            $connectionParams['post'] ?? 5672,
            $connectionParams['user'] ?? 'guest',
            $connectionParams['password'] ?? 'guest',
            $connectionParams['vhost'] ?? '/'
        );
        $this->channel = $this->connection->channel();

        $this->handlerAmount = $handlerAmount;
    }

    /**
     * @param Event $event
     */
    public function publishEvent(Event $event): void
    {
        $routingKey = $event->getClientId() % $this->handlerAmount;
        $this->declareQueue($routingKey);

        $message = new AMQPMessage(
            json_encode($event)
        );

        $this->channel->basic_publish($message, self::EXCHANGE_NAME, $routingKey);
    }

    /**
     * @param int $key
     * @return string
     */
    private function declareQueue(int $key): string
    {
        $exchangeName = self::EXCHANGE_NAME;
        $this->channel->exchange_declare($exchangeName, 'direct', false, true, false);

        $queueName = self::EXCHANGE_NAME . '.queue.' . $key;
        $this->channel->queue_declare($queueName, false, true, false, false);
        $this->channel->queue_bind($queueName, $exchangeName, $key);

        return $queueName;
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
