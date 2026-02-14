<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Persistence;

use Generated\Shared\Transfer\SupplierSearchCriteriaTransfer;
use Generated\Shared\Transfer\SupplierSearchTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchPersistenceFactory getFactory()
 */
class SupplierSearchRepository extends AbstractRepository implements SupplierSearchRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierSearchCriteriaTransfer $supplierSearchCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierSearchTransfer>
     */
    public function getSupplierSearches(SupplierSearchCriteriaTransfer $supplierSearchCriteriaTransfer): array
    {
        $supplierSearchEntities = $this->getFactory()
            ->createSupplierSearchQuery()
            ->filterByFkSupplier_In($supplierSearchCriteriaTransfer->getFksSupplier())
            ->find();

        $supplierSearchTransfers = [];

        foreach ($supplierSearchEntities as $supplierSearchEntity) {
            $supplierSearchTransfers[] = $this->getFactory()
                ->createSupplierSearchMapper()
                ->mapSupplierSearchEntityToSupplierSearchTransfer($supplierSearchEntity, new SupplierSearchTransfer());
        }

        return $supplierSearchTransfers;
    }
}
