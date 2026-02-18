<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\Oms;

use Pyz\Zed\Oms\OmsDependencyProvider as PyzOmsDependencyProvider;
use SprykerAcademy\Zed\Oms\Communication\Plugin\Oms\Command\PayCommandPlugin;
use SprykerAcademy\Zed\Oms\Communication\Plugin\Oms\Condition\IsAuthorizedConditionPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;

class OmsDependencyProvider extends PyzOmsDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendCommandPlugins(Container $container): Container
    {
        $container = parent::extendCommandPlugins($container);

        $container->extend(self::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            $commandCollection->add(new PayCommandPlugin(), 'Demo/Pay');

            return $commandCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendConditionPlugins(Container $container): Container
    {
        $container = parent::extendConditionPlugins($container);

        $container->extend(self::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection->add(new IsAuthorizedConditionPlugin(), 'Demo/IsAuthorized');

            return $conditionCollection;
        });

        return $container;
    }
}
