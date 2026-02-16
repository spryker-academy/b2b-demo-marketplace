<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierLocation\Business;

use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use Generated\Shared\Transfer\SupplierLocationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerAcademy\Zed\SupplierLocation\Business\SupplierLocationBusinessFactory getFactory()
 * @method \SprykerAcademy\Zed\SupplierLocation\Persistence\SupplierLocationRepositoryInterface getRepository()
 */
class SupplierLocationFacade extends AbstractFacade implements SupplierLocationFacadeInterface
{
    public function getSupplierLocations(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): array
    {
        return $this->getFactory()
            ->createSupplierLocationReader()
            ->getSupplierLocations($supplierLocationCriteriaTransfer);
    }

    public function findSupplierLocationById(int $idSupplierLocation): ?SupplierLocationTransfer
    {
        return $this->getFactory()
            ->createSupplierLocationReader()
            ->findSupplierLocationById($idSupplierLocation);
    }
}
