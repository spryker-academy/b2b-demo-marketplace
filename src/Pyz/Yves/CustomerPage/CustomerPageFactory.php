<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\CustomerPage;

use Pyz\Yves\CustomerPage\Form\DataProvider\CheckoutAddressFormDataProvider;
use Pyz\Yves\CustomerPage\Form\FormFactory;
use Pyz\Yves\CustomerPage\Form\Transformer\MessageTransformer;
use Spryker\Client\Session\SessionClientInterface;
use SprykerAcademy\Client\HelloWorld\HelloWorldClientInterface;
use SprykerShop\Yves\CustomerPage\CustomerPageFactory as SprykerCustomerPageFactory;

class CustomerPageFactory extends SprykerCustomerPageFactory
{
    public function createCheckoutAddressFormDataProvider(): CheckoutAddressFormDataProvider
    {
        return new CheckoutAddressFormDataProvider(
            $this->getCustomerClient(),
            $this->getStoreClient(),
            $this->getCustomerService(),
            $this->getShipmentClient(),
            $this->getProductBundleClient(),
            $this->getShipmentService(),
            $this->createAddressChoicesResolver(),
            $this->getCheckoutAddressCollectionFormExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getPyzSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_PYZ_SESSION);
    }

    public function getHelloWorldClient(): HelloWorldClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_HELLO_WORLD);
    }

    public function createMessageTransformer(): MessageTransformer
    {
        return new MessageTransformer(
            $this->getHelloWorldClient(),
        );
    }

    public function createCustomerFormFactory(): FormFactory
    {
        return new FormFactory();
    }
}
