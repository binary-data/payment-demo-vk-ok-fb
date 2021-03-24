<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Domain;

final class OrderStatus
{
    public const NEW = 'new',
        PENDING = 'pending',
        COMPLETED = 'completed'
    ;
}
