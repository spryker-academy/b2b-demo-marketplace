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

        // TODO-3: Make the EventBehaviorFacade and SupplierFacade available to the business layer.
        // Hint-1: Call the addEventBehaviorFacade() and addSupplierFacade() methods.

        return $container;
    }

    // TODO-1: Create the addEventBehaviorFacade method.
    // Hint-1: Call `$container->set()` with `FACADE_EVENT_BEHAVIOR` and a closure.
    // Hint-2: Inside the closure resolve facade from locator: `$container->getLocator()->eventBehavior()->facade()`.

    // TODO-2: Create the addSupplierFacade method.
    // Hint-1: Call `$container->set()` with `FACADE_SUPPLIER` and a closure.
    // Hint-2: Inside the closure resolve facade from locator: `$container->getLocator()->supplier()->facade()`.
}
