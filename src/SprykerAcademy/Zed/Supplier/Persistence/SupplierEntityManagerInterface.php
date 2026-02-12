<?php

namespace Pyz\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;
}
