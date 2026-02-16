<?php

declare(strict_types=1);

namespace SprykerAcademy\Glue\SupplierLocation\Api\Storefront\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Generated\Shared\Transfer\SupplierLocationCriteriaTransfer;
use SprykerAcademy\Client\SupplierLocation\SupplierLocationClientInterface;
use SprykerAcademy\Glue\SupplierLocation\Processor\Mapper\SupplierLocationMapper;

/**
 * @implements \ApiPlatform\State\ProviderInterface<\Generated\Api\Storefront\SupplierLocationsStorefrontResource>
 */
class SupplierLocationsStorefrontProvider implements ProviderInterface
{
    public function __construct(
        protected SupplierLocationClientInterface $supplierLocationClient,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $idSupplierLocation = $uriVariables['idSupplierLocation'] ?? null;

        if ($idSupplierLocation === null) {
            return $this->provideCollection();
        }

        if (!is_numeric($idSupplierLocation)) {
            return null;
        }

        $supplierLocationTransfer = $this->supplierLocationClient->findSupplierLocationById((int)$idSupplierLocation);

        if ($supplierLocationTransfer->getIdSupplierLocation() === null) {
            return null;
        }

        return (new SupplierLocationMapper())->mapTransferToResource($supplierLocationTransfer);
    }

    /**
     * @return array<\Generated\Api\Storefront\SupplierLocationsStorefrontResource>
     */
    protected function provideCollection(): array
    {
        $supplierLocationCollectionTransfer = $this->supplierLocationClient->getSupplierLocations(
            new SupplierLocationCriteriaTransfer(),
        );

        $resources = [];
        $mapper = new SupplierLocationMapper();

        foreach ($supplierLocationCollectionTransfer->getSupplierLocations() as $supplierLocationTransfer) {
            $resources[] = $mapper->mapTransferToResource($supplierLocationTransfer);
        }

        return $resources;
    }
}
