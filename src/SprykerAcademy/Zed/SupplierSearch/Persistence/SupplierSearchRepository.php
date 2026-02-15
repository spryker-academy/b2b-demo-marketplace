<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SupplierSearchCriteriaTransfer;
use Generated\Shared\Transfer\SupplierSearchTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
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
        if ($supplierSearchCriteriaTransfer->getFksSupplier() === []) {
            return [];
        }

        $supplierSearchEntities = $this->getFactory()
            ->createSupplierSearchQuery()
            ->filterByFkSupplier_In($supplierSearchCriteriaTransfer->getFksSupplier())
            ->find();

        $supplierSearchTransfers = [];
        $supplierSearchMapper = $this->getFactory()->createSupplierSearchMapper();

        foreach ($supplierSearchEntities as $supplierSearchEntity) {
            $supplierSearchTransfers[] = $supplierSearchMapper
                ->mapSupplierSearchEntityToSupplierSearchTransfer($supplierSearchEntity, new SupplierSearchTransfer());
        }

        return $supplierSearchTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $supplierIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    #[\Override]
    public function getSynchronizationDataTransfersBySupplierIds(FilterTransfer $filterTransfer, array $supplierIds = []): array
    {
        $supplierSearchEntities = $this->getSupplierSearchEntityCollection($filterTransfer, $supplierIds);
        $synchronizationDataTransfers = [];

        foreach ($supplierSearchEntities as $supplierSearchEntity) {
            $data = $supplierSearchEntity->getData();

            if ($data === null) {
                continue;
            }

            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($data)
                ->setKey($supplierSearchEntity->getKey());
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $supplierIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearch>
     */
    protected function getSupplierSearchEntityCollection(FilterTransfer $filterTransfer, array $supplierIds): ObjectCollection
    {
        $supplierSearchQuery = $this->getFactory()->createSupplierSearchQuery();

        if ($supplierIds !== []) {
            $supplierSearchQuery->filterByFkSupplier_In($supplierIds);
        }

        return $this->buildQueryFromCriteria($supplierSearchQuery, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();
    }
}
