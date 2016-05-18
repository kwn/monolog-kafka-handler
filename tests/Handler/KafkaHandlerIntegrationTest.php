<?php

namespace Kwn\Monolog\Handler;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use RdKafka\Consumer;
use RdKafka\ConsumerTopic;
use RdKafka\Producer;
use RdKafka\ProducerTopic;

class KafkaHandlerIntegrationTest extends \PHPUnit_Framework_TestCase
{
    const BROKER = 'localhost:9092';
    const TOPIC = 'test';
    const PARTITION = 0;
    const MESSAGE = 'test message';

    public function setUp()
    {
        if (!extension_loaded('rdkafka')) {
            $this->markTestSkipped('php-rdkafka extension is not loaded. Please check your php.ini file.');
        }

        list($host, $port) = explode(':', self::BROKER);

        $connection = @fsockopen($host, $port);

        if (false === $connection) {
            $this->markTestSkipped('Kafka broker is not working. Please turn it on in order to pass the test.');
        } else {
            fclose($connection);
        }
    }

    public function testMonologPublishesMessageToKafkaBroker()
    {
        $producerTopic = $this->buildProducerTopic();

        $handler = new KafkaHandler($producerTopic);
        $handler->setFormatter(new LineFormatter('%message%'));

        $monolog = new Logger('kafka-logger');
        $monolog->pushHandler($handler);

        $monolog->critical(self::MESSAGE);

        sleep(2);

        $consumerTopic = $this->buildConsumerTopic();
        $consumerTopic->consumeStart(self::PARTITION, rd_kafka_offset_tail(1));

        $message = $consumerTopic->consume(self::PARTITION, 1000);

        $consumerTopic->consumeStop(self::PARTITION);

        $this->assertEquals(self::MESSAGE, $message->payload);
    }

    /**
     * @return ProducerTopic
     */
    private function buildProducerTopic()
    {
        $producer = new Producer();
        $producer->addBrokers(self::BROKER);

        /** @var ProducerTopic $producerTopic */
        $producerTopic = $producer->newTopic(self::TOPIC);
        $producerTopic->produce(self::PARTITION, 0, 'initial message for a topic creation');

        return $producerTopic;
    }

    /**
     * @return ConsumerTopic
     */
    private function buildConsumerTopic()
    {
        $consumer = new Consumer();
        $consumer->addBrokers(self::BROKER);

        return $consumer->newTopic(self::TOPIC);
    }
}
