<?php

declare(strict_types=1);

namespace SprykerAcademy\Glue\SupplierLocation\Processor\Mapper;

use Generated\Api\Storefront\SupplierLocationsStorefrontResource;
use Generated\Shared\Transfer\SupplierLocationTransfer;

class SupplierLocationMapper
{
    public function mapTransferToResource(SupplierLocationTransfer $supplierLocationTransfer
    ): SupplierLocationsStorefrontResource {
        return SupplierLocationsStorefrontResource::fromArray($supplierLocationTransfer->toArray(false, true));
    }
}
