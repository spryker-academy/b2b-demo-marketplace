<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\Supplier\Communication\Controller;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    public function getSuppliersAction(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierCollectionTransfer
    {
        $supplierTransfers = $this->getFacade()->getSuppliers($supplierCriteriaTransfer);

        return (new SupplierCollectionTransfer())
            ->setSuppliers(new \ArrayObject($supplierTransfers));
    }

    public function findSupplierByIdAction(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierTransfer
    {
        $supplierTransfer = $this->getFacade()->findSupplierById(
            $supplierCriteriaTransfer->getIdSupplierOrFail(),
        );

        return $supplierTransfer ?? new SupplierTransfer();
    }
}
