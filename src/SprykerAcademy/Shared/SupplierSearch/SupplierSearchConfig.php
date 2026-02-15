<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Shared\SupplierSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SupplierSearchConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines queue name as used for processing supplier publish messages.
     *
     * @api
     */
    public const string SUPPLIER_PUBLISH_SEARCH_QUEUE = 'publish.search.supplier';

    /**
     * Specification:
     * - Defines queue name as used for processing supplier sync messages.
     *
     * @api
     */
    public const string SUPPLIER_SYNC_SEARCH_QUEUE = 'sync.search.supplier';

    /**
     * Specification:
     * - Represents pyz_supplier entity creation event.
     *
     * @api
     */
    public const string ENTITY_PYZ_SUPPLIER_CREATE = 'Entity.pyz_supplier.create';

    /**
     * Specification:
     * - Represents pyz_supplier entity change event.
     *
     * @api
     */
    public const string ENTITY_PYZ_SUPPLIER_UPDATE = 'Entity.pyz_supplier.update';

    /**
     * Specification:
     * - Represents pyz_supplier entity deletion event.
     *
     * @api
     */
    public const string ENTITY_PYZ_SUPPLIER_DELETE = 'Entity.pyz_supplier.delete';

    /**
     * Specification:
     * - This event is used for supplier publishing.
     *
     * @api
     */
    public const string SUPPLIER_PUBLISH = 'SupplierSearch.supplier.publish';

    /**
     * Specification:
     * - This event is used for supplier unpublishing.
     *
     * @api
     */
    public const string SUPPLIER_UNPUBLISH = 'SupplierSearch.supplier.unpublish';
}
