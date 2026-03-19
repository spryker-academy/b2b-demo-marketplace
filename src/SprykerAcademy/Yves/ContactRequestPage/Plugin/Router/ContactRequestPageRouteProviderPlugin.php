<?php

namespace SprykerAcademy\Yves\ContactRequestPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ContactRequestPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_NAME_CONTACT_REQUEST = 'contact-request/message/_name_';

    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addContactRequestMessageGetRoute($routeCollection);

        return $routeCollection;
    }

    private function addContactRequestMessageGetRoute(RouteCollection $routeCollection): RouteCollection
    {
        // TODO: Replace the placeholders for module and controller name with the right naming
        // based on src/SprykerAcademy/Yves/ContactRequestPage/Controller/ContactRequestController.php::getAction()
        $route = $this->buildRoute('contact-request/message/{name}', '<module-name>', '<controller-name>', 'getAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_NAME_CONTACT_REQUEST, $route);

        return $routeCollection;
    }
}
