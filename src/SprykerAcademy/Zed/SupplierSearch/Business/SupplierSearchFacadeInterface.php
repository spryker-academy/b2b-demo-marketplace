<?php

namespace Pyz\Zed\SupplierSearch\Business;

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
     *
     * @return void
     */
    public function writeCollectionBySupplierEvents(array $eventTransfers): void;
}
