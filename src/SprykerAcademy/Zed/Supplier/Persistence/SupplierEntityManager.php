<?php

namespace Pyz\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\PyzSupplier;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class SupplierEntityManager extends AbstractEntityManager implements SupplierEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        $supplierEntity = new PyzSupplier();
        $supplierEntity->fromArray($supplierTransfer->modifiedToArray());
        $supplierEntity->save();

        return $supplierTransfer->fromArray($supplierEntity->toArray(), true);
    }
}
