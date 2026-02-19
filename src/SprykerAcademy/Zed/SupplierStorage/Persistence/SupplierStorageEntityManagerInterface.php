<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Persistence;

use Generated\Shared\Transfer\SupplierStorageTransfer;

interface SupplierStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierStorageTransfer $supplierStorageTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierStorageTransfer
     */
    public function createSupplierStorage(SupplierStorageTransfer $supplierStorageTransfer): SupplierStorageTransfer;

    /**
     * @param \Generated\Shared\Transfer\SupplierStorageTransfer $supplierStorageTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierStorageTransfer
     */
    public function updateSupplierStorage(SupplierStorageTransfer $supplierStorageTransfer): SupplierStorageTransfer;
}
