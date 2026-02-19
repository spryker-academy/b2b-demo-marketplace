<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Communication\Plugin\Publisher;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;
use SprykerAcademy\Shared\Supplier\SupplierConfig;

/**
 * @method \SprykerAcademy\Zed\SupplierStorage\Business\SupplierStorageFacadeInterface getFacade()
 * @method \SprykerAcademy\Zed\SupplierStorage\SupplierStorageDependencyProvider getDependencyProvider()
 */
class SupplierWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes supplier data to storage by supplier events.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        // TODO: Call the facade method to write supplier storage collection.
        // Hint: Use $this->getFacade()->writeCollectionBySupplierEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            SupplierConfig::ENTITY_SPY_SUPPLIER_CREATE,
            SupplierConfig::ENTITY_SPY_SUPPLIER_UPDATE,
        ];
    }
}
