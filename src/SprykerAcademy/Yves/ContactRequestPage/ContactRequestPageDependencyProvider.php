<?php

namespace SprykerAcademy\Yves\ContactRequestPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ContactRequestPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONTACT_REQUEST = 'CLIENT_CONTACT_REQUEST';

    public function provideDependencies(Container $container): Container
    {
        $container = $this->addContactRequestClient($container);

        return $container;
    }

    protected function addContactRequestClient(Container $container): Container
    {
        // TODO: Make the ContactRequestClient available
        // Hint: It works exactly like shown in `src/SprykerAcademy/Client/ContactRequest/ContactRequestDependencyProvider.php`
    }
}
