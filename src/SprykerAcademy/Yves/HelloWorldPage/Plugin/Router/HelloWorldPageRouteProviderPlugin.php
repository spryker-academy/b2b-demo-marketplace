<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Yves\HelloWorldPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class HelloWorldPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const string ROUTE_NAME_HELLO_WORLD_MESSAGE_NAME = 'hello-world/message/_idMessage_';

    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addHelloWorldMessageGetRoute($routeCollection);

        return $routeCollection;
    }

    private function addHelloWorldMessageGetRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('hello-world/message/{idMessage}', 'HelloWorldPage', 'Message', 'getAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_NAME_HELLO_WORLD_MESSAGE_NAME, $route);

        return $routeCollection;
    }
}
