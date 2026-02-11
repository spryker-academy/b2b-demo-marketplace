<?php

namespace SprykerAcademy\Yves\HelloWorldPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class HelloWorldPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_HELLO_WORLD = 'CLIENT_HELLO_WORLD';

    public function provideDependencies(Container $container): Container
    {
        $container = $this->addHelloWorldClient($container);

        return $container;
    }

    protected function addHelloWorldClient(Container $container): Container
    {
        // TODO: Make the HelloWorldClient available
        // Hint: It works exactly like shown in `src/SprykerAcademy/Client/HelloWorld/HelloWorldDependencyProvider.php`
    }
}
