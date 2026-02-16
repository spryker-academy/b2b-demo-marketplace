<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierSearch;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerAcademy\Client\SupplierSearch\SupplierSearchFactory getFactory()
 */
class SupplierSearchClient extends AbstractClient implements SupplierSearchClientInterface
{
    public function searchSuppliers(array $requestParameters = []): SupplierCollectionTransfer
    {
        return $this->getFactory()
            ->createSupplierSearchReader()
            ->searchSuppliers($requestParameters);
    }

    public function findSupplierById(int $idSupplier): SupplierTransfer
    {
        return $this->getFactory()
            ->createSupplierSearchReader()
            ->findSupplierById($idSupplier);
    }
}
