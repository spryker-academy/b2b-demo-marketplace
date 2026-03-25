<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Client\ContactRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerAcademy\Client\ContactRequest\Stub\ContactRequestStub;

class ContactRequestFactory extends AbstractFactory
{
    public function createContactRequestStub(): ContactRequestStub
    {
        return new ContactRequestStub($this->getZedRequestClient());
    }

    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(ContactRequestDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
