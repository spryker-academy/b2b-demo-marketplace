<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class SupplierStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_SUPPLIER = 'FACADE_SUPPLIER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        // TODO-3: Make the EventBehaviorFacade and SupplierFacade available to the business layer.
        // Hint-1: Use addEventBehaviorFacade() and addSupplierFacade() methods.

        return $container;
    }

    // TODO-1: Create the addEventBehaviorFacade method.
    // Hint-1: Provide EventBehaviorFacade using the FACADE_EVENT_BEHAVIOR constant.
    // Hint-2: Use $this->getProvidedDependency() to get the facade.

    // TODO-2: Create the addSupplierFacade method.
    // Hint-1: Provide SupplierFacade using the FACADE_SUPPLIER constant.
    // Hint-2: Use $this->getProvidedDependency() to get the facade.
}
