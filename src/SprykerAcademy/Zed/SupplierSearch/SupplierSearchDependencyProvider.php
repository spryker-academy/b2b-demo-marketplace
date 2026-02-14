<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class SupplierSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    public const string FACADE_SUPPLIER = 'FACADE_SUPPLIER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addSupplierFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, static fn (Container $container) => $container->getLocator()->eventBehavior()->facade());

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     */
    protected function addSupplierFacade(Container $container): Container
    {
        $container->set(static::FACADE_SUPPLIER, static fn (Container $container) => $container->getLocator()->supplier()->facade());

        return $container;
    }
}
