<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Client\HelloWorld;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class HelloWorldDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    public const CLIENT_STORE = 'CLIENT_STORE';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addZedRequestClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    protected function addZedRequestClient(Container $container): Container
    {
        // TODO: Make the ZedRequestClient available
        // Hint: Take a look at the `addStoreClient()` method - The only things different are the constant
        // and what you write after the `getLocator()`
    }

    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }
}
