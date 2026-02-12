<?php

namespace Pyz\Zed\Supplier\Business\Writer;

use Generated\Shared\Transfer\SupplierTransfer;
use Pyz\Zed\Supplier\Persistence\SupplierEntityManagerInterface;

class SupplierWriter
{
    protected SupplierEntityManagerInterface $supplierEntityManager;

    /**
     * @param \Pyz\Zed\Supplier\Persistence\SupplierEntityManagerInterface $supplierEntityManager
     */
    public function __construct(SupplierEntityManagerInterface $supplierEntityManager)
    {
        $this->supplierEntityManager = $supplierEntityManager;
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
