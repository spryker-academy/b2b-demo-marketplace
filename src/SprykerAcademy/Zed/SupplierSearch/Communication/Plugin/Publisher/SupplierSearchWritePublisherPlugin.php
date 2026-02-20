<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Communication\Plugin\Publisher;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;

/**
 * @method \SprykerAcademy\Zed\SupplierSearch\Business\SupplierSearchFacadeInterface getFacade()
 */
class SupplierSearchWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    {
        // TODO-1: Use module facade to write collection by supplier events.
        // Hint-1: Use `getFacade()` to access SupplierSearchFacade.
        // Hint-2: Pass `$eventEntityTransfers` to the write method.
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
            // TODO-2: Return event names for supplier publish/create/update.
            // Hint-1: Use constants from SupplierSearchConfig.
        ];
    }
}
