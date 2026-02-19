<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Client\SupplierStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerAcademy\Client\SupplierStorage\SupplierStorageFactory getFactory()
 */
class SupplierStorageClient extends AbstractClient implements SupplierStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSupplier
     *
     * @return array<string, mixed>|null
     */
    public function findSupplierStorageData(int $idSupplier): ?array
    {
        return $this->getFactory()
            ->createSupplierStorageReader()
            ->findSupplierStorageData($idSupplier);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<array<string, mixed>>
     */
    public function getAllSuppliers(): array
    {
        return $this->getFactory()
            ->createSupplierStorageReader()
            ->getAllSuppliers();
    }
}
