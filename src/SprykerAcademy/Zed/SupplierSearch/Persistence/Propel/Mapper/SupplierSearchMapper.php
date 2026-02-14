<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SupplierSearchTransfer;
use Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearch;

class SupplierSearchMapper
{
    /**
     * @param \Generated\Shared\Transfer\SupplierSearchTransfer $supplierSearchTransfer
     * @param \Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearch $supplierSearchEntity
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
     */
    public function mapSupplierSearchEntityToSupplierSearchTransfer(
        PyzSupplierSearch $supplierSearchEntity,
        SupplierSearchTransfer $supplierSearchTransfer,
    ): SupplierSearchTransfer {
        return $supplierSearchTransfer->fromArray($supplierSearchEntity->toArray(), true);
    }
}
