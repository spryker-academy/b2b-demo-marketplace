<?php

namespace Pyz\Glue\SuppliersApi;

use Pyz\Client\SupplierSearch\SupplierSearchClientInterface;
use Pyz\Glue\SuppliersApi\Processor\Mapper\SupplierMapper;
use Pyz\Glue\SuppliersApi\Processor\Reader\SupplierReader;
use Spryker\Glue\Kernel\AbstractStorefrontApiFactory;

class SuppliersApiFactory extends AbstractStorefrontApiFactory
{
    /**
     * @return \Pyz\Glue\SuppliersApi\Processor\Mapper\SupplierMapper
     */
    public function createSupplierMapper(): SupplierMapper
    {
        return new SupplierMapper();
    }

    /**
     * @return \Pyz\Glue\SuppliersApi\Processor\Reader\SupplierReader
     */
    public function createSupplierReader(): SupplierReader
    {
        return new SupplierReader(
            $this->getSupplierSearchClient(),
            $this->createSupplierMapper(),
        );
    }

    /**
     * @return \Pyz\Client\SupplierSearch\SupplierSearchClientInterface
     */
    public function getSupplierSearchClient(): SupplierSearchClientInterface
    {
        return $this->getProvidedDependency(SuppliersApiDependencyProvider::CLIENT_SUPPLIER_SEARCH);
    }
}
