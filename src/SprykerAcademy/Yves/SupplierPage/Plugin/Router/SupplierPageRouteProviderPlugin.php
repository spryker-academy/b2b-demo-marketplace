<?php

namespace Pyz\Yves\SupplierPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SupplierPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const SUPPLIER_INDEX = 'supplier-index';

    /**
     * @inheritDoc
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addSupplierIndexRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    private function addSupplierIndexRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/supplier/{name}', 'SupplierPage', 'Index', 'indexAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::SUPPLIER_INDEX, $route);

        return $routeCollection;
    }
}
