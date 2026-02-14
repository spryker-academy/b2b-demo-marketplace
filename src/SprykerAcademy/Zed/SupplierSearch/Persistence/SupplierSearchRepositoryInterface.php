<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Persistence;

use Generated\Shared\Transfer\SupplierSearchCriteriaTransfer;

interface SupplierSearchRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierSearchCriteriaTransfer $supplierSearchCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SupplierSearchTransfer>
     */
    public function getSupplierSearches(SupplierSearchCriteriaTransfer $supplierSearchCriteriaTransfer): array;
}
