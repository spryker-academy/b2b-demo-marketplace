<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierLocation\Business\Reader;

use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use Generated\Shared\Transfer\SupplierLocationTransfer;
use SprykerAcademy\Zed\SupplierLocation\Persistence\SupplierLocationRepositoryInterface;

readonly class SupplierLocationReader
{
    public function __construct(protected SupplierLocationRepositoryInterface $supplierLocationRepository)
    {
    }

    /**
     * @return list<\Generated\Shared\Transfer\SupplierLocationTransfer>
     */
    public function getSupplierLocations(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): array
    {
        return $this->supplierLocationRepository->getSupplierLocations($supplierLocationCriteriaTransfer);
    }

    public function findSupplierLocationById(int $idSupplierLocation): ?SupplierLocationTransfer
    {
        return $this->supplierLocationRepository->findSupplierLocationById($idSupplierLocation);
    }
}
