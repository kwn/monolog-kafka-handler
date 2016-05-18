# Apache Kafka handler for Monolog 

[![Code Climate](https://codeclimate.com/github/kwn/monolog-kafka-handler/badges/gpa.svg)](https://codeclimate.com/github/kwn/monolog-kafka-handler)

Apache Kafka handler relies on [arnaud-lb/php-rdkafka](https://github.com/arnaud-lb/php-rdkafka) client.

## Installation

In order to install a Kafka handler for Monolog add a dependency to your `composer.json`:

```
{
    "require": {
        "kwn/monolog-kafka-handler": "^1.0.0",
    }
}
```

And update your `composer.lock`:

```
$ php composer.phar update kwn/monolog-kafka-handler
```

Make sure the `php-rdkadka` extension is installed and enabled in your php.ini file. You can also consider installing a [kwn/php-rdkafka-stubs](https://github.com/kwn/php-rdkafka-stubs) package, to provide a set of stubs for `php-rdkafka` in your IDE.

## Usage

In order to use Kafka handler for monolog, you need to create an instance of `\RdKafka\ProducerTopic` object, and inject it via constructor to the Kafka handler. Normally it should happen in your dependency injection container. Simple code showing how to create all necessary elements could be found below:

```php
<?php

use Monolog\Logger;
use RdKafka\Producer;
use Kwn\Monolog\Handler\KafkaHandler;

$producer = new Producer();
$producer->addBrokers('localhost:9092');

$producerTopic = $producer->newTopic('test');

$handler = new KafkaHandler($producerTopic);
$handler->setFormatter(new LineFormatter('%message%'));

$monolog = new Logger('kafka-logger');
$monolog->pushHandler($handler);

$monolog->error('something went wrong');
```
