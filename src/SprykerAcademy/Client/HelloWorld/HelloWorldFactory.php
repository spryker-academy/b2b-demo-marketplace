<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Client\HelloWorld;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerAcademy\Client\HelloWorld\Stub\HelloWorldStub;

class HelloWorldFactory extends AbstractFactory
{
    public function createHelloWorldStub(): HelloWorldStub
    {
        // TODO: Instantiate the HelloWorldStub with the right dependency
        // Hint: You can see the needed parameter(s) for the constructor either through your IDE
        // or by looking into the parent class of HelloWorldStub
    }

    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(HelloWorldDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
