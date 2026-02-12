<?php

namespace Pyz\Zed\SupplierGui;

use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class SupplierGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_ANTELOPE = 'FACADE_ANTELOPE';

    public const PROPEL_QUERY_ANTELOPE = 'PROPEL_QUERY_ANTELOPE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addSupplierPropelQuery($container);
        $container = $this->addSupplierFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSupplierPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_ANTELOPE, $container->factory(function () {
            return PyzSupplierQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSupplierFacade(Container $container): Container
    {
        $container->set(static::FACADE_ANTELOPE, function (Container $container) {
            return $container->getLocator()->supplier()->facade();
        });

        return $container;
    }
}
