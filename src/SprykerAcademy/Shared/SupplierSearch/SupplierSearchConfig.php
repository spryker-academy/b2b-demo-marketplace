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

    /**
     * Specification:
     * - Defines the resource type identifier used in search documents and queries.
     *
     * @api
     */
    public const string SUPPLIER_RESOURCE_TYPE = 'supplier';

    /**
     * Specification:
     * - Defines the Elasticsearch source identifier (index name) for supplier search.
     *
     * @api
     */
    public const string SUPPLIER_SOURCE_IDENTIFIER = 'supplier';

    /**
     * Specification:
     * - Key for the type field in search data.
     *
     * @api
     */
    public const string KEY_TYPE = 'type';

    /**
     * Specification:
     * - Key for the id_supplier field in search data.
     *
     * @api
     */
    public const string KEY_ID_SUPPLIER = 'id_supplier';

    /**
     * Specification:
     * - Key for the name field in search data.
     *
     * @api
     */
    public const string KEY_NAME = 'name';

    /**
     * Specification:
     * - Key for the search-result-data field in search data.
     *
     * @api
     */
    public const string KEY_SEARCH_RESULT_DATA = 'search-result-data';

    /**
     * Specification:
     * - Key for the full-text field in search data.
     *
     * @api
     */
    public const string KEY_FULL_TEXT = 'full-text';

    /**
     * Specification:
     * - Key for the full-text-boosted field in search data.
     *
     * @api
     */
    public const string KEY_FULL_TEXT_BOOSTED = 'full-text-boosted';

    /**
     * Specification:
     * - Key for the suggestion-terms field in search data.
     *
     * @api
     */
    public const string KEY_SUGGESTION_TERMS = 'suggestion-terms';

    /**
     * Specification:
     * - Key for the completion-terms field in search data.
     *
     * @api
     */
    public const string KEY_COMPLETION_TERMS = 'completion-terms';
}
