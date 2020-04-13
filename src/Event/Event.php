<?php

namespace Bothelp\Event;

/**
 * Class Event
 */
final class Event implements \JsonSerializable
{
    /**
     * @var int
     */
    private $clientId;

    /**
     * @var int
     */
    private $orderNum;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * Event constructor.
     * @param int $clientId
     * @param int $orderNum
     * @throws \Exception
     */
    public function __construct(int $clientId, int $orderNum)
    {
        $this->clientId = $clientId;
        $this->orderNum = $orderNum;

        $this->timestamp = new \DateTime();
    }

    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->clientId;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'clientId' => $this->clientId,
            'orderNum' => $this->orderNum,
            'timestamp' => $this->timestamp->format(\DateTime::RFC3339),
        ];
    }
}
