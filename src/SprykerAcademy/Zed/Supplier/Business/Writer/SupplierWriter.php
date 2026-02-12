<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function create(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        return $this->supplierEntityManager->createSupplier($supplierTransfer);
    }
}
