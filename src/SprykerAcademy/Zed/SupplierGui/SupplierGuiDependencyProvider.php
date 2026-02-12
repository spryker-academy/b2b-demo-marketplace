<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\SupplierGui;

use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Override;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class SupplierGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string FACADE_SUPPLIER = 'FACADE_SUPPLIER';

    public const string PROPEL_QUERY_SUPPLIER = 'PROPEL_QUERY_SUPPLIER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    #[Override]
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
        $container->set(static::PROPEL_QUERY_SUPPLIER, $container->factory(function () {
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
        $container->set(static::FACADE_SUPPLIER, function (Container $container) {
            return $container->getLocator()->supplier()->facade();
        });

        return $container;
    }
}
