<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierLocation;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerAcademy\Client\SupplierLocation\Dependency\Client\SupplierLocationToZedRequestClientInterface;
use SprykerAcademy\Client\SupplierLocation\Zed\SupplierLocationStub;
use SprykerAcademy\Client\SupplierLocation\Zed\SupplierLocationStubInterface;

class SupplierLocationFactory extends AbstractFactory
{
    public function createSupplierLocationStub(): SupplierLocationStubInterface
    {
        return new SupplierLocationStub(
            $this->getZedRequestClient(),
        );
    }

    public function getZedRequestClient(): SupplierLocationToZedRequestClientInterface
    {
        return $this->getProvidedDependency(SupplierLocationDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
