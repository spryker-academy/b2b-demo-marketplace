<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Yves\ContactRequestPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerAcademy\Client\ContactRequest\ContactRequestClientInterface;

class ContactRequestPageFactory extends AbstractFactory
{
    public function getContactRequestClient(): ContactRequestClientInterface
    {
        return $this->getProvidedDependency(ContactRequestPageDependencyProvider::CLIENT_CONTACT_REQUEST);
    }
}
