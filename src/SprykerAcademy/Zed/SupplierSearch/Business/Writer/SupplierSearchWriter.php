<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierSearch\Business\Writer;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierSearchCriteriaTransfer;
use Generated\Shared\Transfer\SupplierSearchTransfer;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;
use SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface;
use SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchEntityManagerInterface;
use SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchRepositoryInterface;

readonly class SupplierSearchWriter
{
    /**
     * @param \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface $supplierFacade
     * @param \SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchRepositoryInterface $supplierSearchRepository
     * @param \SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchEntityManagerInterface $supplierSearchEntityManager
     */
    public function __construct(
        protected EventBehaviorFacadeInterface $eventBehaviorFacade,
        protected SupplierFacadeInterface $supplierFacade,
        protected SupplierSearchRepositoryInterface $supplierSearchRepository,
        protected SupplierSearchEntityManagerInterface $supplierSearchEntityManager,
    ) {
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     */
    public function writeCollectionBySupplierEvents(array $eventTransfers): void
    {
        $supplierIds = array_values(array_unique($this->eventBehaviorFacade->getEventTransferIds($eventTransfers)));

        $this->writeCollectionBySupplierIds($supplierIds);
    }

    /**
     * @param array<int> $supplierIds
     */
    protected function writeCollectionBySupplierIds(array $supplierIds): void
    {
        if ($supplierIds === []) {
            return;
        }

        $supplierTransfersIndexed = $this->getSupplierTransfersIndexed($supplierIds);
        $supplierSearchTransfersIndexed = $this->getSupplierSearchTransfersIndexed(
            array_keys($supplierTransfersIndexed),
        );

        foreach ($supplierTransfersIndexed as $supplierId => $supplierTransfer) {
            $searchData = [
                SupplierSearchConfig::KEY_TYPE => SupplierSearchConfig::SUPPLIER_RESOURCE_TYPE,
                SupplierSearchConfig::KEY_ID_SUPPLIER => $supplierTransfer->getIdSupplier(),
                SupplierSearchConfig::KEY_NAME => $supplierTransfer->getName(),
                SupplierSearchConfig::KEY_SEARCH_RESULT_DATA => $supplierTransfer->toArray(),
                SupplierSearchConfig::KEY_FULL_TEXT => [$supplierTransfer->getName()],
                SupplierSearchConfig::KEY_FULL_TEXT_BOOSTED => [$supplierTransfer->getName()],
                SupplierSearchConfig::KEY_SUGGESTION_TERMS => [$supplierTransfer->getName()],
                SupplierSearchConfig::KEY_COMPLETION_TERMS => [$supplierTransfer->getName()],
            ];

            $supplierSearchTransfer = $supplierSearchTransfersIndexed[$supplierId] ?? new SupplierSearchTransfer();

            $supplierSearchTransfer
                ->setFkSupplier($supplierId)
                ->setData($searchData);

            if ($supplierSearchTransfer->getIdSupplierSearch() === null) {
                $this->supplierSearchEntityManager->createSupplierSearch($supplierSearchTransfer);

                continue;
            }

            $this->supplierSearchEntityManager->updateSupplierSearch($supplierSearchTransfer);
        }
    }

    /**
     * @param array<int> $supplierIds
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    protected function getSupplierTransfersIndexed(array $supplierIds): array
    {
        if ($supplierIds === []) {
            return [];
        }

        $supplierCriteriaTransfer = new SupplierCriteriaTransfer()
            ->setIdsSupplier($supplierIds);
        $supplierTransfers = $this->supplierFacade
            ->getSuppliers($supplierCriteriaTransfer);

        $supplierTransfersIndexed = [];
        foreach ($supplierTransfers as $supplierTransfer) {
            $supplierId = $supplierTransfer->getIdSupplier();

            if ($supplierId === null) {
                continue;
            }

            $supplierTransfersIndexed[$supplierId] = $supplierTransfer;
        }

        return $supplierTransfersIndexed;
    }

    /**
     * @param array<int> $supplierIds
     *
     * @return array<\Generated\Shared\Transfer\SupplierSearchTransfer>
     */
    protected function getSupplierSearchTransfersIndexed(array $supplierIds): array
    {
        if ($supplierIds === []) {
            return [];
        }

        $supplierSearchCriteriaTransfer = new SupplierSearchCriteriaTransfer()
            ->setFksSupplier($supplierIds);
        $supplierSearchTransfers = $this->supplierSearchRepository
            ->getSupplierSearches($supplierSearchCriteriaTransfer);

        $supplierSearchTransfersIndexed = [];
        foreach ($supplierSearchTransfers as $supplierSearchTransfer) {
            $supplierId = $supplierSearchTransfer->getFkSupplier();

            if ($supplierId === null) {
                continue;
            }

            $supplierSearchTransfersIndexed[$supplierId] = $supplierSearchTransfer;
        }

        return $supplierSearchTransfersIndexed;
    }
}
