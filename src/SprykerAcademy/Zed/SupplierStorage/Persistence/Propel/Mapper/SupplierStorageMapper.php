<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SupplierStorageTransfer;
use Orm\Zed\SupplierStorage\Persistence\PyzSupplierStorage;

class SupplierStorageMapper
{
    /**
     * @param \Orm\Zed\SupplierStorage\Persistence\PyzSupplierStorage $supplierStorageEntity
     * @param \Generated\Shared\Transfer\SupplierStorageTransfer $supplierStorageTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierStorageTransfer
     */
    public function mapSupplierStorageEntityToSupplierStorageTransfer(
        PyzSupplierStorage $supplierStorageEntity,
        SupplierStorageTransfer $supplierStorageTransfer,
    ): SupplierStorageTransfer {
        $supplierStorageTransfer->fromArray($supplierStorageEntity->toArray(), true);
        $supplierStorageTransfer->setData(
            json_decode($supplierStorageEntity->getData(), true),
        );

        return $supplierStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierStorageTransfer $supplierStorageTransfer
     * @param \Orm\Zed\SupplierStorage\Persistence\PyzSupplierStorage $supplierStorageEntity
     *
     * @return \Orm\Zed\SupplierStorage\Persistence\PyzSupplierStorage
     */
    public function mapSupplierStorageTransferToSupplierStorageEntity(
        SupplierStorageTransfer $supplierStorageTransfer,
        PyzSupplierStorage $supplierStorageEntity,
    ): PyzSupplierStorage {
        $supplierStorageEntity->fromArray($supplierStorageTransfer->toArray());
        $supplierStorageEntity->setData(
            json_encode($supplierStorageTransfer->getData()),
        );

        return $supplierStorageEntity;
    }
}
