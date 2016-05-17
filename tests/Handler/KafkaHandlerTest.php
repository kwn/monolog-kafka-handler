<?php

namespace Kwn\Monolog\Handler;

use Monolog\Logger;
use RdKafka\ProducerTopic;
use Mockery as m;

class KafkaHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProducerTopic
     */
    private $producerTopic;

    /**
     * @var KafkaHandler
     */
    private $kafkaHandler;

    /**
     * @var Logger
     */
    private $monolog;

    public function setUp()
    {
        $this->producerTopic = m::mock(ProducerTopic::class);
        $this->kafkaHandler = new KafkaHandler($this->producerTopic);
        $this->monolog = new Logger('kafka-logger', [$this->kafkaHandler]);
    }

    public function testLogMessage()
    {
        $this->producerTopic->shouldReceive('produce')
            ->with(RD_KAFKA_PARTITION_UA, 0, json_encode(['event' => 'critical alert']))->once();

        $this->monolog->critical(json_encode(['event' => 'critical alert']));
    }
}
