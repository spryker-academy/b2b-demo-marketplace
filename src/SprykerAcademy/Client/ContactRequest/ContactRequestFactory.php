<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Client\ContactRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerAcademy\Client\ContactRequest\Stub\ContactRequestStub;

class ContactRequestFactory extends AbstractFactory
{
    public function createContactRequestStub(): ContactRequestStub
    {
        // TODO: Instantiate the ContactRequestStub with the right dependency
        // Hint: You can see the needed parameter(s) for the constructor either through your IDE
        // or by looking into the parent class of ContactRequestStub
    }

    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(ContactRequestDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
