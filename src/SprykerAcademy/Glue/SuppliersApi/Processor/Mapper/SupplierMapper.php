<?php

namespace Pyz\Glue\SuppliersApi\Processor\Mapper;

use Generated\Shared\Transfer\SuppliersApiAttributesTransfer;
use Generated\Shared\Transfer\SupplierTransfer;

class SupplierMapper
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SuppliersApiAttributesTransfer
     */
    public function mapSupplierTransferToSuppliersApiAttributesTransfer(SupplierTransfer $supplierTransfer): SuppliersApiAttributesTransfer
    {
        return (new SuppliersApiAttributesTransfer())->fromArray($supplierTransfer->toArray(), true);
    }
}
