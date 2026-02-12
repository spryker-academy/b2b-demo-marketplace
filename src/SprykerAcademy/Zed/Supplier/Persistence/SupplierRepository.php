<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Override;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerAcademy\Zed\Supplier\Persistence\SupplierPersistenceFactory getFactory()
 */
class SupplierRepository extends AbstractRepository implements SupplierRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    #[Override]
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): array
    {
        $supplierQuery = $this->getFactory()->createSupplierQuery();

        if ($supplierCriteriaTransfer->getIdsSupplier()) {
            $supplierQuery->filterByIdSupplier_In($supplierCriteriaTransfer->getIdsSupplier());
        }

        $supplierEntities = $supplierQuery->find();

        $supplierTransfers = [];
        $supplierMapper = $this->getFactory()->createSupplierMapper();

        foreach ($supplierEntities as $supplierEntity) {
            $supplierTransfers[] = $supplierMapper->mapSupplierEntityToSupplierTransfer(
                $supplierEntity,
                new SupplierTransfer(),
            );
        }

        return $supplierTransfers;
    }
}
