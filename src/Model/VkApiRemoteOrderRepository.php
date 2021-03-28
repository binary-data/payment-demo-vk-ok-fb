<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Model;

use Binarydata\PaymentDemo\Domain\Vk\RemoteOrder;
use Binarydata\PaymentDemo\Domain\Vk\RemoteOrderRepositoryInterface;
use RuntimeException;

final class VkApiRemoteOrderRepository implements RemoteOrderRepositoryInterface
{
    public function __construct(private string $serviceKey) {}

    public function findAll(int $limit = 100): array
    {
        $params = http_build_query([
            'limit' => $limit,
            'test_mode' => 1,
            'access_token' => $this->serviceKey,
            'v' => '5.130',
        ]);

        $response = json_decode(file_get_contents("https://api.vk.com/method/orders.get?$params"), true, 512, JSON_THROW_ON_ERROR);

        if (isset($response['response'])) {
            return collect($response['response'])
                ->map(fn (array $order) => new RemoteOrder(
                    $order['id'],
                    $order['status'],
                    $order['item'],
                    $order['amount'],
                    $order['date']
                ))
                ->values()
                ->all();
        }

        throw new RuntimeException();
    }
}
