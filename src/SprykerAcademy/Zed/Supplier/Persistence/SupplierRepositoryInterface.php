<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     */
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): array;

    /**
     * @param int $idSupplier
     */
    public function findSupplierById(int $idSupplier): ?SupplierTransfer;
}
