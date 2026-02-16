<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierLocation\Communication\Controller;

use Generated\Shared\Transfer\SupplierLocationCollectionTransfer;
use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use Generated\Shared\Transfer\SupplierLocationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerAcademy\Zed\SupplierLocation\Business\SupplierLocationFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    public function getSupplierLocationsAction(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): SupplierLocationCollectionTransfer
    {
        $supplierLocationTransfers = $this->getFacade()->getSupplierLocations($supplierLocationCriteriaTransfer);

        return (new SupplierLocationCollectionTransfer())
            ->setSupplierLocations(new \ArrayObject($supplierLocationTransfers));
    }

    public function findSupplierLocationByIdAction(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): SupplierLocationTransfer
    {
        $supplierLocationTransfer = $this->getFacade()->findSupplierLocationById(
            $supplierLocationCriteriaTransfer->getIdSupplierLocationOrFail(),
        );

        return $supplierLocationTransfer ?? new SupplierLocationTransfer();
    }
}
