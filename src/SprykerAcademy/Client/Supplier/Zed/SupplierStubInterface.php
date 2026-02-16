<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier\Zed;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierCollectionTransfer
     */
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function findSupplierById(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierTransfer;
}
