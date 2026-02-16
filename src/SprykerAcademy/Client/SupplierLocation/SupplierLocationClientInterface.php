<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierLocation;

use Generated\Shared\Transfer\SupplierLocationCollectionTransfer;
use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use Generated\Shared\Transfer\SupplierLocationTransfer;

interface SupplierLocationClientInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierLocationCollectionTransfer
     */
    public function getSupplierLocations(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): SupplierLocationCollectionTransfer;

    /**
     * @api
     *
     * @param int $idSupplierLocation
     *
     * @return \Generated\Shared\Transfer\SupplierLocationTransfer
     */
    public function findSupplierLocationById(int $idSupplierLocation): SupplierLocationTransfer;
}
