<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierLocation\Zed;

use Generated\Shared\Transfer\SupplierLocationCollectionTransfer;
use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use Generated\Shared\Transfer\SupplierLocationTransfer;

interface SupplierLocationStubInterface
{
    public function getSupplierLocations(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): SupplierLocationCollectionTransfer;

    public function findSupplierLocationById(SupplierLocationCriteriaTransfer $supplierLocationCriteriaTransfer): SupplierLocationTransfer;
}
