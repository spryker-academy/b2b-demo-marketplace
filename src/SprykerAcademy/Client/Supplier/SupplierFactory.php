<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerAcademy\Client\SupplierSearch\SupplierSearchClientInterface;

class SupplierFactory extends AbstractFactory
{
    public function getSupplierSearchClient(): SupplierSearchClientInterface
    {
        return $this->getProvidedDependency(SupplierDependencyProvider::CLIENT_SUPPLIER_SEARCH);
    }
}
