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
        // TODO-1: Generate storage key using synchronization service.
        // Hint: Use $this->synchronizationService->getStorageKeyBuilder('supplier')->buildKey($idSupplier)
        $key = null;

        // TODO-2: Get data from Redis using storage client.
        // Hint: Use $this->storageClient->get($key)
        $supplierStorageData = null;

        if (!$supplierStorageData) {
            return null;
        }

        // TODO-3: Decode JSON data and return.
        // Hint: Use json_decode() with true parameter for associative array
        return null;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getAllSuppliers(): array
    {
        // TODO-4: Get all storage keys matching pattern.
        // Hint: Use $this->synchronizationService->getStorageKeyBuilder('supplier')->buildKey('*')
        // Hint: Use $this->storageClient->getKeys($pattern) to get all keys
        $pattern = null;
        $keys = [];

        $suppliers = [];
        foreach ($keys as $key) {
            // TODO-5: Get each supplier data and decode.
            $data = null;

            if ($data) {
                $suppliers[] = $data;
            }
        }

        return $suppliers;
    }
}
