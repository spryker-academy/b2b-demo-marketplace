<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Client\SupplierStorage\Storage;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;

class SupplierStorageReader
{
    protected StorageClientInterface $storageClient;

    protected SynchronizationServiceInterface $synchronizationService;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        StorageClientInterface $storageClient,
        SynchronizationServiceInterface $synchronizationService,
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idSupplier
     *
     * @return array<string, mixed>|null
     */
    public function findSupplierStorageData(int $idSupplier): ?array
    {
        $key = $this->synchronizationService
            ->getStorageKeyBuilder('supplier')
            ->buildKey((string)$idSupplier);

        $supplierStorageData = $this->storageClient->get($key);

        if (!$supplierStorageData) {
            return null;
        }

        return json_decode($supplierStorageData, true);
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getAllSuppliers(): array
    {
        $pattern = $this->synchronizationService
            ->getStorageKeyBuilder('supplier')
            ->buildKey('*');

        $keys = $this->storageClient->getKeys($pattern);

        $suppliers = [];
        foreach ($keys as $key) {
            $data = $this->storageClient->get($key);

            if ($data) {
                $suppliers[] = json_decode($data, true);
            }
        }

        return $suppliers;
    }
}
