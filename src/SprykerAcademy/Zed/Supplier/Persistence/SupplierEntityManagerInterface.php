<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    public function updateSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    public function deleteSupplier(SupplierTransfer $supplierTransfer): void;
}
