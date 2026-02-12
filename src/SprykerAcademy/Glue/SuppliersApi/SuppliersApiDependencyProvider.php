<?php

namespace Pyz\Glue\SuppliersApi;

use Pyz\Client\SupplierSearch\SupplierSearchClientInterface;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class SuppliersApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SUPPLIER_SEARCH = 'CLIENT_SUPPLIER_SEARCH';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addSupplierSearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSupplierSearchClient(Container $container): Container
    {
        $container[static::CLIENT_SUPPLIER_SEARCH] = function (Container $container): SupplierSearchClientInterface {
            return $container->getLocator()->supplierSearch()->client();
        };

        return $container;
    }
}
