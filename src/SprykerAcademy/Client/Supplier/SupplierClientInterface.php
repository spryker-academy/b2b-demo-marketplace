<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierClientInterface
{
    /**
     * Specification:
     * - Retrieves suppliers from Elasticsearch via SupplierSearchClient.
     *
     * @api
     *
     * @param array<mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SupplierCollectionTransfer
     */
    public function getSuppliers(array $requestParameters = []): SupplierCollectionTransfer;

    /**
     * Specification:
     * - Finds a supplier by ID from Elasticsearch via SupplierSearchClient.
     * - Returns an empty transfer when not found.
     *
     * @api
     *
     * @param int $idSupplier
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function findSupplierById(int $idSupplier): SupplierTransfer;
}
