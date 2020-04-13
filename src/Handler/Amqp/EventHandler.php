<?php

namespace Bothelp\Handler\Amqp;

use Bothelp\Handler\Logger;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class EventHandler
 */
final class EventHandler implements \Bothelp\Handler\EventHandler
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
     * @var Logger
     */
    private $logger;

    /**
     * AmqpQueue constructor.
     * @param array $connectionParams
     * @param Logger $logger
     */
    public function __construct(array $connectionParams, Logger $logger)
    {
        $this->connection = new AMQPStreamConnection(
            $connectionParams['host'] ?? 'localhost',
            $connectionParams['post'] ?? 5672,
            $connectionParams['user'] ?? 'guest',
            $connectionParams['password'] ?? 'guest',
            $connectionParams['vhost'] ?? '/'
        );
        $this->channel = $this->connection->channel();

        $this->logger = $logger;
    }

    /**
     * @param int $key
     * @throws \ErrorException
     */
    public function handle(int $key)
    {
        $queueName = $this->declareQueue($key);

        $this->channel->basic_qos(null, 100, null);

        $callback = function ($message) use ($key) {
            $this->handleMessage($message, $key);
        };
        $this->channel->basic_consume($queueName, '', false, false, false, false, $callback);
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    /**
     * @param AMQPMessage $message
     * @param int $consumerKey
     */
    private function handleMessage(AMQPMessage $message, int $consumerKey)
    {
        $data = json_decode($message->getBody(), true);

        sleep(1);

        $this->logger->logMessage(
            sprintf(
                'ConsumerKey: %s, ClientID: %s, EventNum: %s',
                $consumerKey,
                $data['clientId'],
                $data['orderNum']
            )
        );

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
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
