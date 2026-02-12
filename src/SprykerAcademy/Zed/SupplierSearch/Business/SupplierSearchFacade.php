<?php

namespace Pyz\Zed\SupplierSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\SupplierSearch\Business\SupplierSearchBusinessFactory getFactory()
 * @method \Pyz\Zed\SupplierSearch\Persistence\SupplierSearchRepositoryInterface getRepository()
 * @method \Pyz\Zed\SupplierSearch\Persistence\SupplierSearchEntityManagerInterface getEntityManager()
 */
class SupplierSearchFacade extends AbstractFacade implements SupplierSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionBySupplierEvents(array $eventTransfers): void
    {
        $this->getFactory()->createSupplierSearchWriter()
            ->writeCollectionBySupplierEvents($eventTransfers);    }
}
