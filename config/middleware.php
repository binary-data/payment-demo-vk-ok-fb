<?php

declare(strict_types=1);

use Binarydata\PaymentDemo\Middleware\ErrorHandlerMiddleware;
use Binarydata\PaymentDemo\Middleware\VkResponseMiddleware;
use Binarydata\PaymentDemo\Middleware\VkSignatureMiddleware;
use Binarydata\Shpongle\Http\Middleware\ActionDispatcherMiddleware;
use Binarydata\Shpongle\Http\Middleware\RouterMiddleware;

return [
    ErrorHandlerMiddleware::class,
    VkSignatureMiddleware::class,
    RouterMiddleware::class,
    ActionDispatcherMiddleware::class,
    VkResponseMiddleware::class,
];
