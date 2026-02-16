<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierLocation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SupplierLocationTransfer;
use Orm\Zed\SupplierLocation\Persistence\PyzSupplierLocation;

class SupplierLocationMapper
{
    public function mapSupplierLocationEntityToTransfer(
        PyzSupplierLocation $supplierLocationEntity,
        SupplierLocationTransfer $supplierLocationTransfer,
    ): SupplierLocationTransfer {
        return $supplierLocationTransfer->fromArray($supplierLocationEntity->toArray(), true);
    }
}
