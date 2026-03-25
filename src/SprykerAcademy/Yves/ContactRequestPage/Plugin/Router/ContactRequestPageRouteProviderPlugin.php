<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Yves\ContactRequestPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ContactRequestPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const string ROUTE_NAME_CONTACT_REQUEST = 'contact-request/message/_idMessage_';

    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addContactRequestMessageGetRoute($routeCollection);

        return $routeCollection;
    }

    private function addContactRequestMessageGetRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('contact-request/{idContactRequest}', 'ContactRequestPage', 'Message', 'getAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_NAME_CONTACT_REQUEST, $route);

        return $routeCollection;
    }
}
