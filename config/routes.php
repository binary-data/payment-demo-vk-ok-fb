<?php

declare(strict_types=1);

use Binarydata\PaymentDemo\Action\VkPaymentAction;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

return simpleDispatcher(static function (RouteCollector $r) {
    $r->post('/payment/vk', VkPaymentAction::class);
});
