<?php

namespace Pyz\Zed\Supplier\Business\Reader;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Pyz\Zed\Supplier\Persistence\SupplierRepositoryInterface;

class SupplierReader
{
    protected SupplierRepositoryInterface $supplierRepository;

    /**
     * @param \Pyz\Zed\Supplier\Persistence\SupplierRepositoryInterface $supplierRepository
     */
    public function __construct(SupplierRepositoryInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): array
    {
        return $this->supplierRepository
            ->getSuppliers($supplierCriteriaTransfer);
    }
}
