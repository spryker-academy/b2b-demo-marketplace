<?php

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
