<?php

declare(strict_types=1);

use Binarydata\PaymentDemo\Action\GetOrdersAction;
use Binarydata\PaymentDemo\Action\VkPaymentAction;
use Binarydata\Shpongle\Http\Route\FastRouteCollection;

return (new FastRouteCollection())
    ->get('/orders', GetOrdersAction::class)
    ->post('/payment/vk', VkPaymentAction::class, 'vk')
;
