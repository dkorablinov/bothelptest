<?php

namespace Bothelp\EventQueue\Amqp;

use Bothelp\EventQueue\Event;
use Bothelp\EventQueue\QueueInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class AmqpQueue
 */
final class AmqpQueue implements QueueInterface
{
    /**
     * @param Event $event
     * @throws \Exception
     */
    public function pushEvent(Event $event): void
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
        $channel = $connection->channel();

        $exchangeName = 'bothelp_events';
        $channel->exchange_declare($exchangeName, 'direct', false, true, false);

        $routingKey = $event->getClientId();
        $queueName = 'bothelp_events.queue.' . $routingKey;
        $channel->queue_declare($queueName, false, true, false, false);
        $channel->queue_bind($queueName, $exchangeName, $routingKey);

        $message = new AMQPMessage(
            json_encode($event)
        );

        $channel->basic_publish($message, $exchangeName, $routingKey);

        $channel->close();
        $connection->close();
    }
}
