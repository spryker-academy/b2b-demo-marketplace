<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\Supplier;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class SupplierDependencyProvider extends AbstractDependencyProvider
{
    public const string CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const string CLIENT_SUPPLIER_SEARCH = 'CLIENT_SUPPLIER_SEARCH';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addSupplierSearchClient($container);

        return $container;
    }

    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        });

        return $container;
    }

    protected function addSupplierSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SUPPLIER_SEARCH, function (Container $container) {
            return $container->getLocator()->supplierSearch()->client();
        });

        return $container;
    }
}
