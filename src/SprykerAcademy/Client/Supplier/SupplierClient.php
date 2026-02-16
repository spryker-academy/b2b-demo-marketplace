<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerAcademy\Client\Supplier\SupplierFactory getFactory()
 */
class SupplierClient extends AbstractClient implements SupplierClientInterface
{
    public function getSuppliers(array $requestParameters = []): SupplierCollectionTransfer
    {
        return $this->getFactory()
            ->getSupplierSearchClient()
            ->searchSuppliers($requestParameters);
    }

    public function findSupplierById(int $idSupplier): SupplierTransfer
    {
        $supplierCriteriaTransfer = (new SupplierCriteriaTransfer())
            ->setIdSupplier($idSupplier);

        return $this->getFactory()
            ->createSupplierStub()
            ->findSupplierById($supplierCriteriaTransfer);
    }
}
