<?php

namespace SprykerAcademy\Yves\HelloWorldPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class HelloWorldPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_NAME_HELLO_WORLD_MESSAGE_NAME = 'hello-world/message/_name_';

    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addHelloWorldMessageGetRoute($routeCollection);

        return $routeCollection;
    }

    private function addHelloWorldMessageGetRoute(RouteCollection $routeCollection): RouteCollection
    {
        // TODO: Replace the placeholders for module and controller name with the right naming
        // based on src/SprykerAcademy/Yves/HelloWorldPage/Controller/MessageController.php::getAction()
        $route = $this->buildRoute('hello-world/message/{name}', '<module-name>', '<controller-name>', 'getAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_NAME_HELLO_WORLD_MESSAGE_NAME, $route);

        return $routeCollection;
    }
}
