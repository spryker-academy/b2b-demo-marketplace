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
        // TODO-1: Use SupplierStorageReader to find supplier by ID.
        // Hint: Get the reader from factory.
        return null;
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
        // TODO-2: Use SupplierStorageReader to get all suppliers.
        // Hint: Get the reader from factory.
        return [];
    }
}
