<?php

namespace Pyz\Zed\SupplierSearch\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SupplierSearchTransfer;
use Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearch;

class SupplierSearchMapper
{
    /**
     * @param \Generated\Shared\Transfer\SupplierSearchTransfer $supplierSearchTransfer
     * @param \Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearch $supplierSearchEntity
     *
     * @return \Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearch
     */
    public function mapSupplierSearchTransferToSupplierSearchEntity(
        SupplierSearchTransfer $supplierSearchTransfer,
        PyzSupplierSearch $supplierSearchEntity,
    ): PyzSupplierSearch {
        return $supplierSearchEntity->fromArray($supplierSearchTransfer->modifiedToArray());
    }

    /**
     * @param \Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearch $supplierSearchEntity
     * @param \Generated\Shared\Transfer\SupplierSearchTransfer $supplierSearchTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierSearchTransfer
     */
    public function mapSupplierSearchEntityToSupplierSearchTransfer(
        PyzSupplierSearch $supplierSearchEntity,
        SupplierSearchTransfer $supplierSearchTransfer,
    ): SupplierSearchTransfer {
        return $supplierSearchTransfer->fromArray($supplierSearchEntity->toArray(), true);
    }
}
