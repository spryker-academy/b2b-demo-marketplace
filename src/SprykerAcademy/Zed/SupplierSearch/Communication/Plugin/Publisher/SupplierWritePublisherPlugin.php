<?php

namespace Pyz\Zed\SupplierSearch\Communication\Plugin\Publisher;

use Pyz\Shared\SupplierSearch\SupplierSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Pyz\Zed\SupplierSearch\Business\SupplierSearchFacadeInterface getFacade()
 */
class SupplierWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
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
            SupplierSearchConfig::SUPPLIER_PUBLISH,
            SupplierSearchConfig::ENTITY_PYZ_SUPPLIER_CREATE,
            SupplierSearchConfig::ENTITY_PYZ_SUPPLIER_UPDATE,
        ];
    }
}
