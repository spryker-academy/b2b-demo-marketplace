<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Shared\Supplier;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SupplierConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Event name for when a supplier entity is created.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SUPPLIER_CREATE = 'Entity.pyz_supplier.create';

    /**
     * Specification:
     * - Event name for when a supplier entity is updated.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SUPPLIER_UPDATE = 'Entity.pyz_supplier.update';

    /**
     * Specification:
     * - Event name for when a supplier entity is deleted.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SUPPLIER_DELETE = 'Entity.pyz_supplier.delete';

    /**
     * Specification:
     * - Queue name for supplier publish events.
     *
     * @api
     *
     * @var string
     */
    public const SUPPLIER_SYNC_STORAGE_QUEUE = 'sync.storage.supplier';
}
