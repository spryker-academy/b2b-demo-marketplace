<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Business\Writer;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierStorageCriteriaTransfer;
use Generated\Shared\Transfer\SupplierStorageTransfer;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface;
use SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageEntityManagerInterface;
use SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageRepositoryInterface;

class SupplierStorageWriter
{
    protected EventBehaviorFacadeInterface $eventBehaviorFacade;

    protected SupplierFacadeInterface $supplierFacade;

    protected SupplierStorageRepositoryInterface $supplierStorageRepository;

    protected SupplierStorageEntityManagerInterface $supplierStorageEntityManager;

    /**
     * @param \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface $supplierFacade
     * @param \SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageRepositoryInterface $supplierStorageRepository
     * @param \SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageEntityManagerInterface $supplierStorageEntityManager
     */
    public function __construct(
        EventBehaviorFacadeInterface $eventBehaviorFacade,
        SupplierFacadeInterface $supplierFacade,
        SupplierStorageRepositoryInterface $supplierStorageRepository,
        SupplierStorageEntityManagerInterface $supplierStorageEntityManager,
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->supplierFacade = $supplierFacade;
        $this->supplierStorageRepository = $supplierStorageRepository;
        $this->supplierStorageEntityManager = $supplierStorageEntityManager;
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
        $supplierStorageTransfersIndexed = $this->getSupplierStorageTransfersIndexed(
            array_keys($supplierTransfersIndexed),
        );

        foreach ($supplierTransfersIndexed as $supplierId => $supplierTransfer) {
            $storageData = $supplierTransfer->toArray();

            $supplierStorageTransfer = $supplierStorageTransfersIndexed[$supplierId] ?? new SupplierStorageTransfer();

            $supplierStorageTransfer
                ->setFkSupplier($supplierId)
                ->setData($storageData);

            if ($supplierStorageTransfer->getIdSupplierStorage() === null) {
                $this->supplierStorageEntityManager->createSupplierStorage($supplierStorageTransfer);

                continue;
            }

            $this->supplierStorageEntityManager->updateSupplierStorage($supplierStorageTransfer);
        }
    }

    /**
     * @param array<int> $supplierIds
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    protected function getSupplierTransfersIndexed(array $supplierIds): array
    {
        $supplierCriteriaTransfer = (new SupplierCriteriaTransfer())
            ->setIdsSupplier($supplierIds);

        $supplierTransfers = $this->supplierFacade->getSupplierCollection($supplierCriteriaTransfer);

        $supplierTransfersIndexed = [];
        foreach ($supplierTransfers as $supplierTransfer) {
            $supplierTransfersIndexed[$supplierTransfer->getIdSupplier()] = $supplierTransfer;
        }

        return $supplierTransfersIndexed;
    }

    /**
     * @param array<int> $supplierIds
     *
     * @return array<\Generated\Shared\Transfer\SupplierStorageTransfer>
     */
    protected function getSupplierStorageTransfersIndexed(array $supplierIds): array
    {
        $supplierStorageCriteriaTransfer = (new SupplierStorageCriteriaTransfer())
            ->setFksSupplier($supplierIds);

        $supplierStorageTransfers = $this->supplierStorageRepository->getSupplierStorageCollection(
            $supplierStorageCriteriaTransfer,
        );

        $supplierStorageTransfersIndexed = [];
        foreach ($supplierStorageTransfers as $supplierStorageTransfer) {
            $supplierStorageTransfersIndexed[$supplierStorageTransfer->getFkSupplier()] = $supplierStorageTransfer;
        }

        return $supplierStorageTransfersIndexed;
    }
}
