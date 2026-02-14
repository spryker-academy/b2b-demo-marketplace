<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Yves\HelloWorldPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class HelloWorldPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string CLIENT_HELLO_WORLD = 'CLIENT_HELLO_WORLD';

    public function provideDependencies(Container $container): Container
    {
        $container = $this->addHelloWorldClient($container);

        return $container;
    }

    protected function addHelloWorldClient(Container $container): Container
    {
        $container->set(static::CLIENT_HELLO_WORLD, fn () => $container->getLocator()->helloWorld()->client());

        return $container;
    }
}
