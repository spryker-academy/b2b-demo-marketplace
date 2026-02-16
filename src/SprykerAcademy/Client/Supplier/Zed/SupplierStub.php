<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier\Zed;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class SupplierStub implements SupplierStubInterface
{
    public function __construct(
        protected ZedRequestClientInterface $zedRequestClient,
    ) {
    }

    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\SupplierCollectionTransfer $supplierCollectionTransfer */
        $supplierCollectionTransfer = $this->zedRequestClient->call(
            '/supplier/gateway/get-suppliers',
            $supplierCriteriaTransfer,
        );

        return $supplierCollectionTransfer;
    }

    public function findSupplierById(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierTransfer
    {
        /** @var \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer */
        $supplierTransfer = $this->zedRequestClient->call(
            '/supplier/gateway/find-supplier-by-id',
            $supplierCriteriaTransfer,
        );

        return $supplierTransfer;
    }
}
