<?php

declare(strict_types=1);

use Binarydata\PaymentDemo\Middleware\ErrorHandlerMiddleware;
use Binarydata\PaymentDemo\Middleware\VkSignatureMiddleware;
use Binarydata\Shpongle\Http\Middleware\ActionDispatcherMiddleware;
use Binarydata\Shpongle\Http\Middleware\RouterMiddleware;
use Binarydata\Shpongle\Http\Route\RouteCollectionInterface;

return [
    RouteCollectionInterface::GROUP_DEFAULT => [
        ErrorHandlerMiddleware::class,
        RouterMiddleware::class,
        ActionDispatcherMiddleware::class,
    ],

    'vk' => [
        VkSignatureMiddleware::class,
    ],
];
