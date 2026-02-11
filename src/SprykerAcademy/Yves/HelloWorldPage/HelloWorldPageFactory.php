<?php

namespace SprykerAcademy\Yves\HelloWorldPage;

use SprykerAcademy\Client\HelloWorld\HelloWorldClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class HelloWorldPageFactory extends AbstractFactory
{
    public function getHelloWorldClient(): HelloWorldClientInterface
    {
        // TODO: Get the provided dependency for the HelloWorldClient
        // Hint-1: Have a look at src/SprykerAcademy/Client/HelloWorld/HelloWorldFactory.php::getZedRequestClient() for the right syntax
        // Hint-2: The name of the constant to use is 'HelloWorldPageDependencyProvider::CLIENT_HELLO_WORLD'
    }
}
