<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Business;

interface SupplierSearchFacadeInterface
{
    /**
     * Specification:
     * - Retrieves all suppliers using IDs from $eventTransfers.
     * - Updates entities from `pyz_supplier_search` with actual data from obtained suppliers.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     */
    public function writeCollectionBySupplierEvents(array $eventTransfers): void;
}
