<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;

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

    /**
     * Specification:
     * - Reads entities from `pyz_supplier_search` based on criteria from FilterTransfer and supplier IDs.
     * - Returns array of SynchronizationDataTransfer filled with data from search entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $supplierIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersBySupplierIds(FilterTransfer $filterTransfer, array $supplierIds = []): array;
}
