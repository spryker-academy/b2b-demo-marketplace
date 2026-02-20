<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Communication\Plugin\Publisher;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;
use SprykerAcademy\Shared\SupplierStorage\SupplierStorageConfig;

/**
 * @method \SprykerAcademy\Zed\SupplierStorage\Business\SupplierStorageFacadeInterface getFacade()
 * @method \SprykerAcademy\Zed\SupplierStorage\SupplierStorageDependencyProvider getDependencyProvider()
 */
class SupplierStorageWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
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
        $this->getFacade()->writeCollectionBySupplierEvents($eventEntityTransfers);
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
            SupplierStorageConfig::SUPPLIER_PUBLISH,
            SupplierStorageConfig::ENTITY_PYZ_SUPPLIER_CREATE,
            SupplierStorageConfig::ENTITY_PYZ_SUPPLIER_UPDATE,
        ];
    }
}
