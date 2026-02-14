<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Business\Writer;

use Generated\Shared\Transfer\SupplierTransfer;
use SprykerAcademy\Zed\Supplier\Persistence\SupplierEntityManagerInterface;

readonly class SupplierWriter
{
    /**
     * @param \SprykerAcademy\Zed\Supplier\Persistence\SupplierEntityManagerInterface $supplierEntityManager
     */
    public function __construct(protected SupplierEntityManagerInterface $supplierEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    public function create(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        return $this->supplierEntityManager->createSupplier($supplierTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    public function update(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        return $this->supplierEntityManager->updateSupplier($supplierTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    public function delete(SupplierTransfer $supplierTransfer): void
    {
        $this->supplierEntityManager->deleteSupplier($supplierTransfer);
    }
}
