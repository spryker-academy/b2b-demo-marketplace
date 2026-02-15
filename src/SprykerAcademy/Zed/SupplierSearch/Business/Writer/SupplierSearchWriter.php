<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Business\Writer;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierSearchCriteriaTransfer;
use Generated\Shared\Transfer\SupplierSearchTransfer;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface;
use SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchEntityManagerInterface;
use SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchRepositoryInterface;

class SupplierSearchWriter
{
    protected EventBehaviorFacadeInterface $eventBehaviorFacade;

    protected SupplierFacadeInterface $supplierFacade;

    protected SupplierSearchRepositoryInterface $supplierSearchRepository;

    protected SupplierSearchEntityManagerInterface $supplierSearchEntityManager;

    /**
     * @param \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface $supplierFacade
     * @param \SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchRepositoryInterface $supplierSearchRepository
     * @param \SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchEntityManagerInterface $supplierSearchEntityManager
     */
    public function __construct(
        EventBehaviorFacadeInterface $eventBehaviorFacade,
        SupplierFacadeInterface $supplierFacade,
        SupplierSearchRepositoryInterface $supplierSearchRepository,
        SupplierSearchEntityManagerInterface $supplierSearchEntityManager,
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->supplierFacade = $supplierFacade;
        $this->supplierSearchRepository = $supplierSearchRepository;
        $this->supplierSearchEntityManager = $supplierSearchEntityManager;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     */
    public function writeCollectionBySupplierEvents(array $eventTransfers): void
    {
        $supplierIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->writeCollectionBySupplierIds($supplierIds);
    }

    /**
     * @param array<int> $supplierIds
     */
    protected function writeCollectionBySupplierIds(array $supplierIds): void
    {
        if (!$supplierIds) {
            return;
        }

        $supplierTransfersIndexed = $this->getSupplierTransfersIndexed($supplierIds);
        $supplierSearchTransfersIndexed = $this->getSupplierSearchTransfersIndexed(
            array_keys($supplierTransfersIndexed),
        );

        foreach ($supplierTransfersIndexed as $supplierId => $supplierTransfer) {
            $searchData = $supplierTransfer->toArray();

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
        // TODO-1: Create SupplierCriteriaTransfer and populate it with `$supplierIds`.
        // Hint-1: Use `setIdsSupplier()`.
        $supplierCriteriaTransfer = null;

        // TODO-2: Use SupplierFacade to fetch suppliers by ids.
        // Hint-1: Pass the criteria transfer created above.
        $supplierTransfers = null;

        $supplierTransfersIndexed = [];
        foreach ($supplierTransfers as $supplierTransfer) {
            $supplierTransfersIndexed[$supplierTransfer->getIdSupplier()] = $supplierTransfer;
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
        // TODO-3: Create SupplierSearchCriteriaTransfer and populate it with `$supplierIds`.
        // Hint-1: Use `setFksSupplier()`.
        $supplierSearchCriteriaTransfer = null;

        // TODO-4: Use SupplierSearchRepository to load SupplierSearch transfers.
        // Hint-1: Pass the criteria transfer created above.
        $supplierSearchTransfers = null;

        $supplierSearchTransfersIndexed = [];
        foreach ($supplierSearchTransfers as $supplierSearchTransfer) {
            $supplierSearchTransfersIndexed[$supplierSearchTransfer->getFkSupplier()] = $supplierSearchTransfer;
        }

        return $supplierSearchTransfersIndexed;
    }
}
