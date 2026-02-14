<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\PyzSupplier;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class SupplierEntityManager extends AbstractEntityManager implements SupplierEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    #[\Override]
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        $supplierEntity = new PyzSupplier();
        $supplierMapper = $this->getFactory()->createSupplierMapper();
        $supplierEntity = $supplierMapper
            ->mapSupplierTransferToSupplierEntity($supplierTransfer, $supplierEntity);
        $supplierEntity->save();

        return $supplierMapper
            ->mapSupplierEntityToSupplierTransfer($supplierEntity, new SupplierTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    #[\Override]
    public function updateSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        $idSupplier = $supplierTransfer->getIdSupplier();

        if ($idSupplier === null) {
            return $supplierTransfer;
        }

        $supplierEntity = $this->getFactory()
            ->createSupplierQuery()
            ->filterByIdSupplier($idSupplier)
            ->findOne();

        if ($supplierEntity === null) {
            return $supplierTransfer;
        }

        $supplierMapper = $this->getFactory()
            ->createSupplierMapper();
        $supplierEntity = $supplierMapper
            ->mapSupplierTransferToSupplierEntity($supplierTransfer, $supplierEntity);
        $supplierEntity->save();

        return $supplierMapper
            ->mapSupplierEntityToSupplierTransfer($supplierEntity, new SupplierTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    #[\Override]
    public function deleteSupplier(SupplierTransfer $supplierTransfer): void
    {
        $idSupplier = $supplierTransfer->getIdSupplier();

        if ($idSupplier === null) {
            return;
        }

        $supplierEntity = $this->getFactory()
            ->createSupplierQuery()
            ->filterByIdSupplier($idSupplier)
            ->findOne();

        if ($supplierEntity === null) {
            return;
        }

        $supplierEntity->delete();
    }
}
