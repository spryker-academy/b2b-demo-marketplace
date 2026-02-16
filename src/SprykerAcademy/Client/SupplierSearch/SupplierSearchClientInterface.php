<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierSearch;

use Generated\Shared\Transfer\SupplierCollectionTransfer;

interface SupplierSearchClientInterface
{
    /**
     * Specification:
     * - Searches suppliers in Elasticsearch.
     * - Returns a SupplierCollectionTransfer with matching suppliers.
     *
     * @api
     *
     * @param array<mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SupplierCollectionTransfer
     */
    public function searchSuppliers(array $requestParameters = []): SupplierCollectionTransfer;
}
