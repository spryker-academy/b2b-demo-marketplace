<?php

namespace Pyz\Zed\SupplierSearch\Persistence;

use Generated\Shared\Transfer\SupplierSearchCriteriaTransfer;

interface SupplierSearchRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierSearchCriteriaTransfer $supplierSearchCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierSearchTransfer>
     */
    public function getSupplierSearches(SupplierSearchCriteriaTransfer $supplierSearchCriteriaTransfer): array;
}
