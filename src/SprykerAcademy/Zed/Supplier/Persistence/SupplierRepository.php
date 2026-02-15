<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use SprykerAcademy\Zed\Supplier\Persistence\Propel\Mapper\SupplierMapper;

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

        $supplierMapper = $this->getFactory()->createSupplierMapper();

        return $this->mapSupplierEntitiesToTransfers($supplierEntities, $supplierMapper);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<int, \Orm\Zed\Supplier\Persistence\PyzSupplier> $supplierEntities
     * @param \SprykerAcademy\Zed\Supplier\Persistence\Propel\Mapper\SupplierMapper $supplierMapper
     *
     * @return list<\Generated\Shared\Transfer\SupplierTransfer>
     */
    protected function mapSupplierEntitiesToTransfers(
        ObjectCollection $supplierEntities,
        SupplierMapper $supplierMapper,
    ): array {
        $supplierTransfers = [];

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
