<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Propel\Mapper\SupplierMapper;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerAcademy\Zed\Supplier\Persistence\SupplierPersistenceFactory getFactory()
 */
class SupplierRepository extends AbstractRepository implements SupplierRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     */
    #[\Override]
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): array
    {
        $supplierQuery = $this->getFactory()->createSupplierQuery();

        if ($supplierCriteriaTransfer->getIdsSupplier()) {
            $supplierQuery->filterByIdSupplier_In($supplierCriteriaTransfer->getIdsSupplier());
        }

        if ($supplierCriteriaTransfer->getName() !== null) {
            $supplierQuery->filterByName($supplierCriteriaTransfer->getName());
        }

        $supplierEntities = $supplierQuery->find();

        $supplierTransfers = [];
        $supplierMapper = $this->getFactory()->createSupplierMapper();

        return $this->getSupplierTransfers($supplierEntities, $supplierMapper, $supplierTransfers);
    }

    /**
     * @param mixed $supplierEntities
     * @param \SprykerAcademy\Zed\Supplier\Persistence\Propel\Mapper\SupplierMapper $supplierMapper
     * @param array $supplierTransfers
     */
    public function getSupplierTransfers(
        mixed $supplierEntities,
        SupplierMapper $supplierMapper,
        array $supplierTransfers,
    ): array {
        foreach ($supplierEntities as $supplierEntity) {
            $supplierTransfers[] = $supplierMapper->mapSupplierEntityToSupplierTransfer(
                $supplierEntity,
                new SupplierTransfer(),
            );
        }

        return $supplierTransfers;
    }

    /**
     * @param int $idSupplier
     */
    #[\Override]
    public function findSupplierById(int $idSupplier): ?SupplierTransfer
    {
        $supplierEntity = $this->getFactory()
            ->createSupplierQuery()
            ->filterByIdSupplier($idSupplier)
            ->findOne();

        if ($supplierEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createSupplierMapper()
            ->mapSupplierEntityToSupplierTransfer($supplierEntity, new SupplierTransfer());
    }
}
