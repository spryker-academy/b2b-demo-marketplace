<?php

declare(strict_types = 1);

namespace Pyz\Zed\Customer\Communication;

use Pyz\Zed\Customer\CustomerDependencyProvider;
use SprykerAcademy\Zed\HelloWorld\Business\HelloWorldFacadeInterface;
use Spryker\Zed\Customer\Communication\CustomerCommunicationFactory as SprykerCustomerCommunicationFactory;

class CustomerCommunicationFactory extends SprykerCustomerCommunicationFactory
{
    public function getHelloWorldFacade(): HelloWorldFacadeInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_HELLO_WORLD);
    }
}
