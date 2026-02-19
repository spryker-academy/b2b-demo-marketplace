<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Client\SupplierStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class SupplierStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        // TODO-2: Add storage client and synchronization service.
        // Hint: Use addStorageClient() and addSynchronizationService()

        return $container;
    }

    // TODO-1: Create addStorageClient() method.
    // Hint: Provide StorageClient using CLIENT_STORAGE constant

    // TODO-1: Create addSynchronizationService() method.
    // Hint: Provide SynchronizationService using SERVICE_SYNCHRONIZATION constant
}
