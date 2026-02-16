<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierLocation;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use SprykerAcademy\Client\SupplierLocation\Dependency\Client\SupplierLocationToZedRequestClientBridge;
use SprykerAcademy\Client\SupplierLocation\Dependency\Client\SupplierLocationToZedRequestClientInterface;

class SupplierLocationDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addZedRequestClient($container);

        return $container;
    }

    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container): SupplierLocationToZedRequestClientInterface {
            return new SupplierLocationToZedRequestClientBridge(
                $container->getLocator()->zedRequest()->client(),
            );
        });

        return $container;
    }
}
