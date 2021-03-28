<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use Binarydata\PaymentDemo\Domain\OrderRepositoryInterface;
use Binarydata\PaymentDemo\Domain\Vk\RemoteOrderRepositoryInterface;
use Binarydata\PaymentDemo\Middleware\VkSignatureMiddleware;
use Binarydata\PaymentDemo\Model\CycleOrderRepository;
use Binarydata\PaymentDemo\Model\VkApiRemoteOrderRepository;
use Binarydata\Shpongle\Service\Config\ConfigInterface;
use Cycle\ORM\ORM;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

return [
    VkSignatureMiddleware::class => function (
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        ConfigInterface $config
    ) {
        return new VkSignatureMiddleware($responseFactory, $streamFactory, $config->get('vk.secret', ''));
    },

    OrderRepositoryInterface::class => fn (CycleOrderRepository $r) => $r,

    ORM::class => function () {
        require_once ROOT . 'config' . DIRECTORY_SEPARATOR . 'cycle_orm_bootstrap.php';
        /** @noinspection PhpUndefinedVariableInspection */
        return $orm;
    },

    RemoteOrderRepositoryInterface::class => function (ConfigInterface $c) {
        return new VkApiRemoteOrderRepository($c->get('vk.serviceKey', ''));
    }
];
