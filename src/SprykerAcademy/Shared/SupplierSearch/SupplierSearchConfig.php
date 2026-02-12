<?php

namespace Pyz\Shared\SupplierSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SupplierSearchConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines queue name as used for processing supplier publish messages.
     *
     * @api
     *
     * @var string
     */
    public const SUPPLIER_PUBLISH_SEARCH_QUEUE = 'publish.search.supplier';

    /**
     * Specification:
     * - Defines queue name as used for processing supplier sync messages.
     *
     * @api
     *
     * @var string
     */
    public const SUPPLIER_SYNC_SEARCH_QUEUE = 'sync.search.supplier';

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

    /**
     * Specification:
     * - This event is used for supplier publishing.
     *
     * @api
     *
     * @var string
     */
    public const SUPPLIER_PUBLISH = 'SupplierSearch.supplier.publish';

    /**
     * Specification:
     * - This event is used for supplier unpublishing.
     *
     * @api
     *
     * @var string
     */
    public const SUPPLIER_UNPUBLISH = 'SupplierSearch.supplier.unpublish';
}
