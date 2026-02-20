<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SupplierStorageCriteriaTransfer;

interface SupplierStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierStorageCriteriaTransfer $supplierStorageCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierStorageTransfer>
     */
    public function getSupplierStorageCollection(
        SupplierStorageCriteriaTransfer $supplierStorageCriteriaTransfer,
    ): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $supplierStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSupplierStorageSynchronizationDataTransfers(
        FilterTransfer $filterTransfer,
        array $supplierStorageIds = [],
    ): array;
}
