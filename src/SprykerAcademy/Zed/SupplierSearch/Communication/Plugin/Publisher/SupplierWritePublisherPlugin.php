<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierSearch\Communication\Plugin\Publisher;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;

/**
 * @method \SprykerAcademy\Zed\SupplierSearch\Business\SupplierSearchFacadeInterface getFacade()
 */
class SupplierWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     * @api
     *
     */
    #[\Override]
    public function handleBulk(
        array $eventEntityTransfers,
        $eventName
    ): void // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    {
        $this->getFacade()->writeCollectionBySupplierEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     * @api
     *
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
