<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Business;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierFacadeInterface
{
    /**
     * - Creates a new supplier into the database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): array;

    /**
     * Specification:
     * - Retrieves supplier collection by criteria.
     * - Returns array of SupplierTransfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    public function getSupplierCollection(SupplierCriteriaTransfer $supplierCriteriaTransfer): array;

    /**
     * - Finds a supplier by id.
     * - Returns null when a supplier is not found.
     *
     * @api
     *
     * @param int $idSupplier
     */
    public function findSupplierById(int $idSupplier): ?SupplierTransfer;

    /**
     * - Updates an existing supplier.
     * - Requires `SupplierTransfer.idSupplier`.
     * - Returns the updated supplier transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    public function updateSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;

    /**
     * - Deletes supplier by `SupplierTransfer.idSupplier`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    public function deleteSupplier(SupplierTransfer $supplierTransfer): void;
}
