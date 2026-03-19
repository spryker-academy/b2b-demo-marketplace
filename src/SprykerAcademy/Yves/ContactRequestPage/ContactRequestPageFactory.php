<?php

namespace SprykerAcademy\Yves\ContactRequestPage;

use SprykerAcademy\Client\ContactRequest\ContactRequestClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class ContactRequestPageFactory extends AbstractFactory
{
    public function getContactRequestClient(): ContactRequestClientInterface
    {
        // TODO: Get the provided dependency for the ContactRequestClient
        // Hint-1: Have a look at src/SprykerAcademy/Client/ContactRequest/ContactRequestFactory.php::getZedRequestClient() for the right syntax
        // Hint-2: The name of the constant to use is 'ContactRequestPageDependencyProvider::CLIENT_CONTACT_REQUEST'
    }
}
