<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Client\SupplierStorage;

interface SupplierStorageClientInterface
{
    /**
     * Specification:
     * - Finds supplier data in storage by supplier ID.
     * - Returns supplier data or null if not found.
     *
     * @api
     *
     * @param int $idSupplier
     *
     * @return array<string, mixed>|null
     */
    public function findSupplierStorageData(int $idSupplier): ?array;

    /**
     * Specification:
     * - Returns all suppliers from storage.
     *
     * @api
     *
     * @return array<array<string, mixed>>
     */
    public function getAllSuppliers(): array;
}
