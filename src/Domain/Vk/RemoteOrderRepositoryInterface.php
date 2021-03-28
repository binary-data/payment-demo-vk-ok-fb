<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Domain\Vk;

interface RemoteOrderRepositoryInterface
{
    /**
     * @param int $limit
     * @return RemoteOrder[]
     */
    public function findAll(int $limit = 100): array;
}
