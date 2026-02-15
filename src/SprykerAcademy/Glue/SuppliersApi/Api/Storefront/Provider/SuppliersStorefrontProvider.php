<?php

declare(strict_types = 1);

namespace SprykerAcademy\Glue\SuppliersApi\Api\Storefront\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Generated\Shared\Transfer\SupplierTransfer;
use SprykerAcademy\Glue\SuppliersApi\Processor\Mapper\SupplierMapper;

/**
 * @implements \ApiPlatform\State\ProviderInterface<object>
 */
class SuppliersStorefrontProvider implements ProviderInterface
{
    public function __construct(protected SupplierMapper $supplierMapper)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // TODO-1: Read the supplier identifier from uri variables.
        // Hint-1: Use the resource identifier key from `suppliers.resource.yml`.
        $idSupplier = $uriVariables['idSupplier'] ?? null;

        if ($idSupplier === null) {
            // TODO-2: Return a supplier collection.
            // Hint-1: Use the Supplier facade/client to load all suppliers for the collection endpoint.
            return [];
        }

        // TODO-3: Load the supplier by identifier.
        // Hint-1: Use `findSupplierById()` from the Supplier facade/client.
        $supplierTransfer = null;

        if (!$supplierTransfer instanceof SupplierTransfer) {
            return null;
        }

        // TODO-4: Return a mapped API Platform resource.
        // Hint-1: Use SupplierMapper::mapSupplierTransferToSuppliersStorefrontResource().
        return null;
    }
}
