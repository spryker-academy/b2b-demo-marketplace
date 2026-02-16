<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerAcademy\Client\Supplier\Zed\SupplierStub;
use SprykerAcademy\Client\Supplier\Zed\SupplierStubInterface;
use SprykerAcademy\Client\SupplierSearch\SupplierSearchClientInterface;

class SupplierFactory extends AbstractFactory
{
    public function createSupplierStub(): SupplierStubInterface
    {
        return new SupplierStub(
            $this->getZedRequestClient(),
        );
    }

    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(SupplierDependencyProvider::CLIENT_ZED_REQUEST);
    }

    public function getSupplierSearchClient(): SupplierSearchClientInterface
    {
        return $this->getProvidedDependency(SupplierDependencyProvider::CLIENT_SUPPLIER_SEARCH);
    }
}
