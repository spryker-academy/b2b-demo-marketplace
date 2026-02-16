<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierLocation\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface SupplierLocationToZedRequestClientInterface
{
    public function call(string $url, TransferInterface $object, ?array $requestOptions = null): TransferInterface;
}
