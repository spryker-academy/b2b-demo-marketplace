<?php

namespace Pyz\Yves\SupplierPage;

use Pyz\Client\SupplierSearch\SupplierSearchClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class SupplierPageFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\SupplierSearch\SupplierSearchClientInterface
     */
    public function getSupplierSearchClient(): SupplierSearchClientInterface
    {
        return $this->getProvidedDependency(SupplierPageDependencyProvider::CLIENT_SUPPLIER_SEARCH);
    }
}
