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
     * - Queue name as used for processing supplier publish messages
     *
     * @api
     *
     * @var string
     */
    public const SUPPLIER_PUBLISH_STORAGE_QUEUE = 'publish.storage.supplier';

    /**
     * Specification:
     * - Queue name as used for processing supplier sync messages
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

    /**
     * Specification:
     * - This event is used for supplier publishing to storage.
     *
     * @api
     *
     * @var string
     */
    public const SUPPLIER_PUBLISH = 'SupplierStorage.supplier.publish';

    /**
     * Specification:
     * - Represents pyz_supplier entity creation event.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_PYZ_SUPPLIER_CREATE = 'Entity.pyz_supplier.create';

    /**
     * Specification:
     * - Represents pyz_supplier entity change event.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_PYZ_SUPPLIER_UPDATE = 'Entity.pyz_supplier.update';

    /**
     * Specification:
     * - Represents pyz_supplier entity deletion event.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_PYZ_SUPPLIER_DELETE = 'Entity.pyz_supplier.delete';
}
