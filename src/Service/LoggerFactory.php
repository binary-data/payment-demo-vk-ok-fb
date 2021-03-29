<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Service;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    private array $loggers = [];

    public function create(string $channel): LoggerInterface
    {
        if (! isset($this->loggers[$channel])) {
            $this->loggers[$channel] = new Logger($channel, [new RotatingFileHandler(ROOT . "log/$channel.log")]);
        }

        return $this->loggers[$channel];
    }
}
