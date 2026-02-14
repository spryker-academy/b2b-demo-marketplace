<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Persistence;

use Generated\Shared\Transfer\SupplierSearchTransfer;

interface SupplierSearchEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierSearchTransfer $supplierSearchTransfer
     */
    public function createSupplierSearch(SupplierSearchTransfer $supplierSearchTransfer): SupplierSearchTransfer;

    /**
     * @param \Generated\Shared\Transfer\SupplierSearchTransfer $supplierSearchTransfer
     *
     * @throws \SprykerAcademy\Zed\SupplierSearch\Persistence\Exception\SupplierSearchNotFoundException
     */
    public function updateSupplierSearch(SupplierSearchTransfer $supplierSearchTransfer): SupplierSearchTransfer;
}
