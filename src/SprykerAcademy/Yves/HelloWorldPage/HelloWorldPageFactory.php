<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

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
