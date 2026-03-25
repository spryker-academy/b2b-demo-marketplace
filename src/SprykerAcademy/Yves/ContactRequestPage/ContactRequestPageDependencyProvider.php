<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Yves\ContactRequestPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ContactRequestPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string CLIENT_CONTACT_REQUEST = 'CLIENT_CONTACT_REQUEST';

    public function provideDependencies(Container $container): Container
    {
        $container = $this->addContactRequestClient($container);

        return $container;
    }

    protected function addContactRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONTACT_REQUEST, fn () => $container->getLocator()->contactRequest()->client());

        return $container;
    }
}
