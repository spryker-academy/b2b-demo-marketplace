<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierLocation;

use Generated\Shared\Transfer\SupplierLocationCollectionTransfer;
use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use Generated\Shared\Transfer\SupplierLocationTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerAcademy\Client\SupplierLocation\SupplierLocationFactory getFactory()
 */
class SupplierLocationClient extends AbstractClient implements SupplierLocationClientInterface
{
    public function getSupplierLocations(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer
    ): SupplierLocationCollectionTransfer {
        return $this->getFactory()
            ->createSupplierLocationStub()
            ->getSupplierLocations($supplierLocationCriteriaTransfer);
    }

    public function findSupplierLocationById(int $idSupplierLocation): SupplierLocationTransfer
    {
        $supplierLocationCriteriaTransfer = new SupplierLocationCriteriaTransfer()
            ->setIdSupplierLocation($idSupplierLocation);

        return $this->getFactory()
            ->createSupplierLocationStub()
            ->findSupplierLocationById($supplierLocationCriteriaTransfer);
    }
}
