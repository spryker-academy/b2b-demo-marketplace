<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerAcademy\Zed\SupplierStorage\Business\SupplierStorageBusinessFactory getFactory()
 * @method \SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageRepositoryInterface getRepository()
 * @method \SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageEntityManagerInterface getEntityManager()
 */
class SupplierStorageFacade extends AbstractFacade implements SupplierStorageFacadeInterface
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
        $this->getFactory()
            ->createSupplierStorageWriter()
            ->writeCollectionBySupplierEvents($eventTransfers);
    }
}
