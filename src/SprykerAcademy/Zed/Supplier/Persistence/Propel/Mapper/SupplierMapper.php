<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\PyzSupplier;

class SupplierMapper
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     * @param \Orm\Zed\Supplier\Persistence\PyzSupplier $supplierEntity
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
     */
    public function mapSupplierEntityToSupplierTransfer(
        PyzSupplier $supplierEntity,
        SupplierTransfer $supplierTransfer,
    ): SupplierTransfer {
        return $supplierTransfer->fromArray($supplierEntity->toArray(), true);
    }
}
