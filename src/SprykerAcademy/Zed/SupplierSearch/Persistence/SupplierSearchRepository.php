<?php

namespace Pyz\Zed\SupplierSearch\Persistence;

use Generated\Shared\Transfer\SupplierSearchCriteriaTransfer;
use Generated\Shared\Transfer\SupplierSearchTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\SupplierSearch\Persistence\SupplierSearchPersistenceFactory getFactory()
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
            ->filterByfkSupplier_In($supplierSearchCriteriaTransfer->getFksSupplier())
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
