<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierLocation\Business;

use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use Generated\Shared\Transfer\SupplierLocationTransfer;

interface SupplierLocationFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\SupplierLocationTransfer>
     */
    public function getSupplierLocations(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): array;

    /**
     * @api
     *
     * @param int $idSupplierLocation
     *
     * @return \Generated\Shared\Transfer\SupplierLocationTransfer|null
     */
    public function findSupplierLocationById(int $idSupplierLocation): ?SupplierLocationTransfer;
}
