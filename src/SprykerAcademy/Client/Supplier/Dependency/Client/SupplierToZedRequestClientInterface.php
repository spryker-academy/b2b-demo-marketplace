<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface SupplierToZedRequestClientInterface
{
    /**
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array<mixed>|null $requestOptions
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call(string $url, TransferInterface $object, ?array $requestOptions = null): TransferInterface;
}
