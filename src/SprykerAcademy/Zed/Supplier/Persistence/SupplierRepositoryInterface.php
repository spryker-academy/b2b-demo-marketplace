<?php

namespace Pyz\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;

interface SupplierRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): array;
}
