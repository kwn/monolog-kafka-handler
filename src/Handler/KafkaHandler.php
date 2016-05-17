<?php

namespace Kwn\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use RdKafka\ProducerTopic;

class KafkaHandler extends AbstractProcessingHandler
{
    /**
     * @var ProducerTopic
     */
    private $topic;

    /**
     * @param ProducerTopic $topic
     * @param bool|int      $level
     * @param bool          $bubble
     */
    public function __construct(ProducerTopic $topic, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->topic = $topic;
    }

    /**
     * @param array $record
     *
     * @return void
     */
    protected function write(array $record)
    {
        $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $record['formatted']);
    }
}
