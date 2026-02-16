<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerAcademy\Client\Supplier\Dependency\Client\SupplierToZedRequestClientInterface;
use SprykerAcademy\Client\Supplier\Zed\SupplierStub;
use SprykerAcademy\Client\Supplier\Zed\SupplierStubInterface;

class SupplierFactory extends AbstractFactory
{
    public function createSupplierStub(): SupplierStubInterface
    {
        return new SupplierStub(
            $this->getZedRequestClient(),
        );
    }

    public function getZedRequestClient(): SupplierToZedRequestClientInterface
    {
        return $this->getProvidedDependency(SupplierDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
