<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Client\SupplierStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use SprykerAcademy\Client\SupplierStorage\Storage\SupplierStorageReader;

/**
 * @method \SprykerAcademy\Client\SupplierStorage\SupplierStorageConfig getConfig()
 */
class SupplierStorageFactory extends AbstractFactory
{
    /**
     * @return \SprykerAcademy\Client\SupplierStorage\Storage\SupplierStorageReader
     */
    public function createSupplierStorageReader(): SupplierStorageReader
    {
        // TODO-3: Provide dependencies to SupplierStorageReader.
        // Hint: getStorageClient() and getSynchronizationService()
        return new SupplierStorageReader();
    }

    // TODO-1: Create getStorageClient() method.
    // Hint: Use $this->getProvidedDependency() with STORAGE_CLIENT constant

    // TODO-2: Create getSynchronizationService() method.
    // Hint: Use $this->getProvidedDependency() with SYNCHRONIZATION_SERVICE constant
}
