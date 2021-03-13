<?php


namespace MockingMagician\CoinbaseProSdk\Functional\Websocket;


use Amp\Websocket\Client\Connection;
use MockingMagician\CoinbaseProSdk\Contracts\Websocket\MessageInterface;
use MockingMagician\CoinbaseProSdk\Contracts\Websocket\SubscriberInterface;
use MockingMagician\CoinbaseProSdk\Contracts\Websocket\WebsocketRunnerInterface;
use MockingMagician\CoinbaseProSdk\Functional\Misc\Json;
use function Amp\Promise\wait;

class WebsocketRunner implements WebsocketRunnerInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function subscribe(SubscriberInterface $subscriber): void
    {
        wait($this->connection->send($subscriber->getJsonDescription()));
    }

    public function unsubscribe(SubscriberInterface $subscriber): void
    {
        /**
         * TODO change type to unsubscribe
         */
        wait($this->connection->send($subscriber->getJsonDescription()));
    }

    public function getMessage(): MessageInterface
    {
        $message = wait($this->connection->receive());
        $payload = wait($message->buffer());

        return MessageHandler::handle(Json::decode($payload));
    }
}
