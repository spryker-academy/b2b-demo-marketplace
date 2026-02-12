<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\Supplier\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\PyzSupplier;

class SupplierMapper
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     * @param \Orm\Zed\Supplier\Persistence\PyzSupplier $supplierEntity
     *
     * @return \Orm\Zed\Supplier\Persistence\PyzSupplier
     */
    public function mapSupplierTransferToSupplierEntity(
        SupplierTransfer $supplierTransfer,
        PyzSupplier $supplierEntity,
    ): PyzSupplier {
        return $supplierEntity->fromArray($supplierTransfer->modifiedToArray());
    }

    /**
     * @param \Orm\Zed\Supplier\Persistence\PyzSupplier $supplierEntity
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function mapSupplierEntityToSupplierTransfer(
        PyzSupplier $supplierEntity,
        SupplierTransfer $supplierTransfer,
    ): SupplierTransfer {
        return $supplierTransfer->fromArray($supplierEntity->toArray(), true);
    }
}
