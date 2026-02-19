<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Persistence;

use Generated\Shared\Transfer\SupplierStorageCriteriaTransfer;
use Generated\Shared\Transfer\SupplierStorageTransfer;
use Orm\Zed\SupplierStorage\Persistence\PyzSupplierStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStoragePersistenceFactory getFactory()
 */
class SupplierStorageRepository extends AbstractRepository implements SupplierStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierStorageCriteriaTransfer $supplierStorageCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierStorageTransfer>
     */
    public function getSupplierStorageCollection(
        SupplierStorageCriteriaTransfer $supplierStorageCriteriaTransfer,
    ): array {
        $supplierStorageQuery = PyzSupplierStorageQuery::create();

        if ($supplierStorageCriteriaTransfer->getFksSupplier()) {
            $supplierStorageQuery->filterByFkSupplier_In($supplierStorageCriteriaTransfer->getFksSupplier());
        }

        $supplierStorageEntities = $supplierStorageQuery->find();

        $supplierStorageTransfers = [];
        foreach ($supplierStorageEntities as $supplierStorageEntity) {
            $supplierStorageTransfers[] = $this->getFactory()
                ->createSupplierStorageMapper()
                ->mapSupplierStorageEntityToSupplierStorageTransfer(
                    $supplierStorageEntity,
                    new SupplierStorageTransfer(),
                );
        }

        return $supplierStorageTransfers;
    }
}
