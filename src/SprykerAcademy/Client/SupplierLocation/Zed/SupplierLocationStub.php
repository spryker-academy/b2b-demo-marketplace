<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierLocation\Zed;

use Generated\Shared\Transfer\SupplierLocationCollectionTransfer;
use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use Generated\Shared\Transfer\SupplierLocationTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class SupplierLocationStub implements SupplierLocationStubInterface
{
    public function __construct(
        protected ZedRequestClientInterface $zedRequestClient,
    ) {
    }

    public function getSupplierLocations(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): SupplierLocationCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\SupplierLocationCollectionTransfer $supplierLocationCollectionTransfer */
        $supplierLocationCollectionTransfer = $this->zedRequestClient->call(
            '/supplier-location/gateway/get-supplier-locations',
            $supplierLocationCriteriaTransfer,
        );

        return $supplierLocationCollectionTransfer;
    }

    public function findSupplierLocationById(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): SupplierLocationTransfer
    {
        /** @var \Generated\Shared\Transfer\SupplierLocationTransfer $supplierLocationTransfer */
        $supplierLocationTransfer = $this->zedRequestClient->call(
            '/supplier-location/gateway/find-supplier-location-by-id',
            $supplierLocationCriteriaTransfer,
        );

        return $supplierLocationTransfer;
    }
}
