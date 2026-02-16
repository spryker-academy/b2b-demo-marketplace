<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierClientInterface
{
    /**
     * Specification:
     * - Retrieves suppliers from Zed via RPC call.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierCollectionTransfer
     */
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierCollectionTransfer;

    /**
     * Specification:
     * - Finds a supplier by ID via RPC call to Zed.
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
