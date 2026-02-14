<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Business\Reader;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use SprykerAcademy\Zed\Supplier\Persistence\SupplierRepositoryInterface;

readonly class SupplierReader
{
    /**
     * @param \SprykerAcademy\Zed\Supplier\Persistence\SupplierRepositoryInterface $supplierRepository
     */
    public function __construct(protected SupplierRepositoryInterface $supplierRepository)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierCriteriaTransfer $supplierCriteriaTransfer
     */
    public function getSuppliers(SupplierCriteriaTransfer $supplierCriteriaTransfer): array
    {
        return $this->supplierRepository
            ->getSuppliers($supplierCriteriaTransfer);
    }

    /**
     * @param int $idSupplier
     */
    public function findSupplierById(int $idSupplier): ?SupplierTransfer
    {
        return $this->supplierRepository->findSupplierById($idSupplier);
    }
}
