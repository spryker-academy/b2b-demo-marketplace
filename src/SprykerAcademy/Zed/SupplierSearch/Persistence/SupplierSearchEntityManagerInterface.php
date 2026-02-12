<?php

namespace Pyz\Zed\SupplierSearch\Persistence;

use Generated\Shared\Transfer\SupplierSearchTransfer;

interface SupplierSearchEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierSearchTransfer $supplierSearchTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierSearchTransfer
     */
    public function createSupplierSearch(SupplierSearchTransfer $supplierSearchTransfer): SupplierSearchTransfer;

    /**
     * @param \Generated\Shared\Transfer\SupplierSearchTransfer $supplierSearchTransfer
     *
     * @throws \Pyz\Zed\SupplierSearch\Persistence\Exception\SupplierSearchNotFoundException
     *
     * @return \Generated\Shared\Transfer\SupplierSearchTransfer
     */
    public function updateSupplierSearch(SupplierSearchTransfer $supplierSearchTransfer): SupplierSearchTransfer;
}
