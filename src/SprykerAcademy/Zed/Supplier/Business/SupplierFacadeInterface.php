<?php

namespace Pyz\Zed\Supplier\Business;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierFacadeInterface
{
    /**
     * Specification:
     * - Creates a new supplier into the database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): array;
}
