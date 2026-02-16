<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierLocation\Persistence;

use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use Generated\Shared\Transfer\SupplierLocationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerAcademy\Zed\SupplierLocation\Persistence\SupplierLocationPersistenceFactory getFactory()
 */
class SupplierLocationRepository extends AbstractRepository implements SupplierLocationRepositoryInterface
{
    public function getSupplierLocations(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): array
    {
        $query = $this->getFactory()->createSupplierLocationQuery();

        if ($supplierLocationCriteriaTransfer->getFkSupplier() !== null) {
            $query->filterByFkSupplier($supplierLocationCriteriaTransfer->getFkSupplier());
        }

        $entities = $query->find();
        $mapper = $this->getFactory()->createSupplierLocationMapper();
        $transfers = [];

        foreach ($entities as $entity) {
            $transfers[] = $mapper->mapSupplierLocationEntityToTransfer($entity, new SupplierLocationTransfer());
        }

        return $transfers;
    }

    public function findSupplierLocationById(int $idSupplierLocation): ?SupplierLocationTransfer
    {
        $entity = $this->getFactory()
            ->createSupplierLocationQuery()
            ->filterByIdSupplierLocation($idSupplierLocation)
            ->findOne();

        if ($entity === null) {
            return null;
        }

        return $this->getFactory()
            ->createSupplierLocationMapper()
            ->mapSupplierLocationEntityToTransfer($entity, new SupplierLocationTransfer());
    }
}
