<?php

namespace Pyz\Yves\SupplierPage;

use Pyz\Client\SupplierSearch\SupplierSearchClientInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class SupplierPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SUPPLIER_SEARCH = 'CLIENT_SUPPLIER_SEARCH';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addSupplierSearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSupplierSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SUPPLIER_SEARCH, function (Container $container): SupplierSearchClientInterface {
            return $container->getLocator()->supplierSearch()->client();
        });

        return $container;
    }
}
