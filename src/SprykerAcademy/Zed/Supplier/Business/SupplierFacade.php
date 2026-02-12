<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\Supplier\Business;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Override;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerAcademy\Zed\Supplier\Business\SupplierBusinessFactory getFactory()
 * @method \SprykerAcademy\Zed\Supplier\Persistence\SupplierRepositoryInterface getRepository()
 * @method \SprykerAcademy\Zed\Supplier\Persistence\SupplierEntityManagerInterface getEntityManager()
 */
class SupplierFacade extends AbstractFacade implements SupplierFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    #[Override]
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        return $this->getFactory()
            ->createSupplierWriter()
            ->create($supplierTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    #[Override]
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): array
    {
        return $this->getFactory()
            ->createSupplierReader()
            ->getSuppliers($supplierCriteriaTransfer);
    }
}
