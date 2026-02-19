<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Shared\SupplierStorage;

interface SupplierStorageConfig
{
    /**
     * Specification:
     * - Queue name as used for processing supplier messages
     *
     * @api
     *
     * @var string
     */
    public const SUPPLIER_SYNC_STORAGE_QUEUE = 'sync.storage.supplier';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     *
     * @var string
     */
    public const SUPPLIER_RESOURCE_NAME = 'supplier';
}
