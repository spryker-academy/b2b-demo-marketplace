<?php

namespace SprykerAcademy\Yves\HelloWorldPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerAcademy\Client\HelloWorld\HelloWorldClientInterface;

class HelloWorldPageFactory extends AbstractFactory
{


    public function getHelloWorldClient(): HelloWorldClientInterface
    {
        return $this->getProvidedDependency(HelloWorldPageDependencyProvider::CLIENT_HELLO_WORLD);
    }
}
