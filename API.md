# Creating a Glue Storefront API Module

This guide walks through creating a Glue Storefront API resource end-to-end, using the **Supplier** module as a reference. It covers two data access strategies: reading from **Elasticsearch** (for collections) and **ZedRequest RPC** (for single-record lookups), plus the Glue API resource layer.

## Architecture Overview

Glue Storefront runs in a **separate application context** from Zed. Propel (database ORM) is **not available** in Glue. Data access must go through Client modules.

There are two strategies for reading data in Glue:

### Strategy 1: Elasticsearch (recommended for collections / storefront reads)

```
GET /suppliers
    |
    v
Glue Provider
    |
    v
SupplierClient::getSuppliers()
    |
    v
SupplierSearchClient::searchSuppliers()
    |  Reader -> QueryPlugin -> SearchClient -> ResultFormatter
    v
Elasticsearch (supplier index)
```

### Strategy 2: ZedRequest RPC (for single-record lookups or write operations)

```
GET /suppliers/{id}
    |
    v
Glue Provider
    |
    v
SupplierClient::findSupplierById()
    |
    v
SupplierStub (calls /supplier/gateway/find-supplier-by-id)
    |
    v
Zed GatewayController -> Facade -> Repository -> Propel -> Database
```

**Key rules:**
- Never use Propel queries, Zed Facades, or Orm entities directly in Glue.
- Don't use bridge classes outside `vendor/spryker`. Provide clients directly via the locator.
- Use `toArray(false, true)` when mapping transfers to API resources (camelCased keys).
- **Always use constants** from Config classes instead of magic strings for field names, index names, queue names, and event names.

---

## Quick Reference

### Essential Commands

```bash
# Generate transfers and API resources
docker/sdk cli console transfer:generate
docker/sdk cli console glue api:generate

# Publish data to Elasticsearch
docker/sdk cli console publish:trigger-events -r supplier
docker/sdk cli console queue:worker:start --stop-when-empty

# Clear caches
docker/sdk cli console cache:empty-all

# Test endpoints
curl http://glue-storefront.eu.spryker.local/suppliers
curl http://glue-storefront.eu.spryker.local/suppliers/1
```

### Key Files Checklist

When creating a new Glue API resource, you need:

- [ ] **Transfer definitions** — `Shared/{Module}/Transfer/{module}.transfer.xml`
- [ ] **Zed Gateway Controller** — `Zed/{Module}/Communication/Controller/GatewayController.php` (for RPC)
- [ ] **Search table schema** — `Zed/{ModuleSearch}/Persistence/Propel/Schema/pyz_{module}_search.schema.xml`
- [ ] **Search Writer** — `Zed/{ModuleSearch}/Business/Writer/{Module}SearchWriter.php`
- [ ] **Publisher plugins** — Write + Trigger plugins in `Zed/{ModuleSearch}/Communication/Plugin/Publisher/`
- [ ] **Search QueryPlugin** — `Client/{ModuleSearch}/Plugin/Elasticsearch/Query/{Module}SearchQueryPlugin.php`
- [ ] **ResultFormatter** — `Client/{ModuleSearch}/Plugin/Elasticsearch/ResultFormatter/{Module}SearchResultFormatterPlugin.php`
- [ ] **Search Client** — `Client/{ModuleSearch}/{ModuleSearch}Client.php`
- [ ] **Client Stub** — `Client/{Module}/Zed/{Module}Stub.php` (for RPC)
- [ ] **Client facade** — `Client/{Module}/{Module}Client.php`
- [ ] **API Resource YAML** — `Glue/{Module}/resources/api/storefront/{resources}.resource.yml`
- [ ] **Provider** — `Glue/{Module}/Api/Storefront/Provider/{Resources}StorefrontProvider.php`
- [ ] **Mapper** — `Glue/{Module}/Processor/Mapper/{Module}Mapper.php`
- [ ] **DI registration** — `config/GlueStorefront/ApplicationServices.php`
- [ ] **Publisher registration** — `Pyz/Zed/Publisher/PublisherDependencyProvider.php`

### Troubleshooting Quick Checks

```bash
# Check ES data (use the supplier index)
curl "http://localhost:10005/de_supplier/_search?pretty"

# Check sync queue
docker/sdk cli console queue:task:start sync.search.supplier -s 1 -l 5

# Check database
docker/sdk cli console propel:sql:execute "SELECT * FROM pyz_supplier_search LIMIT 1"

# Verify routes
docker/sdk cli console debug:router | grep supplier

# Check logs
docker/sdk cli tail -f /data/logs/GLUE-STOREFRONT/application.log
```

---

## Step-by-Step Guide

### 1. Define Transfers

All data exchanged between layers uses Transfer objects. Define them in `src/SprykerAcademy/Shared/{Module}/Transfer/{module}.transfer.xml`.

You need a **Collection transfer** to wrap arrays for both ZedRequest serialization and Elasticsearch result formatting.

```xml
<!-- src/SprykerAcademy/Shared/Supplier/Transfer/supplier.transfer.xml -->
<?xml version="1.0"?>
<transfers xmlns="spryker:transfer-01"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">

    <transfer name="Supplier">
        <property name="idSupplier" type="int" />
        <property name="name" type="string" />
        <property name="description" type="string" />
        <property name="status" type="int" />
        <property name="email" type="string" />
        <property name="phone" type="string" />
    </transfer>

    <transfer name="SupplierCollection">
        <property name="suppliers" type="Supplier[]" singular="supplier" />
    </transfer>

    <transfer name="SupplierCriteria">
        <property name="idSupplier" type="int" />
        <property name="name" type="string" />
    </transfer>
</transfers>
```

Generate transfers:
```bash
docker/sdk cli console transfer:generate
```

### 2. Configuration Constants (Shared Config)

**Critical: Define all constants in a shared Config class before writing any implementation code.**

Create `SupplierSearchConfig` in the `Shared` namespace so it's accessible from all layers (Zed, Client, Glue).

```php
// src/SprykerAcademy/Shared/SupplierSearch/SupplierSearchConfig.php
namespace SprykerAcademy\Shared\SupplierSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SupplierSearchConfig extends AbstractBundleConfig
{
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
     * - This event is used for supplier publishing.
     *
     * @api
     */
    public const string SUPPLIER_PUBLISH = 'SupplierSearch.supplier.publish';

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

    // Field name constants for search documents
    public const string KEY_TYPE = 'type';
    public const string KEY_ID_SUPPLIER = 'id_supplier';
    public const string KEY_NAME = 'name';
    public const string KEY_SEARCH_RESULT_DATA = 'search-result-data';
    public const string KEY_FULL_TEXT = 'full-text';
    public const string KEY_FULL_TEXT_BOOSTED = 'full-text-boosted';
    public const string KEY_SUGGESTION_TERMS = 'suggestion-terms';
    public const string KEY_COMPLETION_TERMS = 'completion-terms';
}
```

**Why use constants?**
1. **Single source of truth** — Change the value once, updates everywhere
2. **Type safety** — PHP 8.3+ typed constants prevent typos
3. **IDE support** — Auto-completion and refactoring tools work properly
4. **Maintainability** — Easy to find all usages and track dependencies
5. **Consistency** — Ensures all layers use the same values

**Where to use these constants:**
- Schema XML files (via copy-paste, XML doesn't support PHP constants)
- Writer classes (for structuring search data)
- QueryPlugin (for index name and field filtering)
- ResultFormatter (for reading nested data)
- Publisher plugins (for event names and queue names)
- DependencyProvider (for queue registration)

### 3. Zed Business Layer

Standard Spryker Zed architecture. Required for ZedRequest RPC and for the publish & sync pipeline that feeds Elasticsearch.

Key files:
- `Zed/{Module}/Persistence/{Module}Repository.php` — reads from DB via Propel
- `Zed/{Module}/Persistence/{Module}PersistenceFactory.php` — creates Propel queries and mappers
- `Zed/{Module}/Business/Reader/{Module}Reader.php` — business logic for reads
- `Zed/{Module}/Business/{Module}BusinessFactory.php` — creates readers/writers
- `Zed/{Module}/Business/{Module}Facade.php` — public API

### 4. Zed Gateway Controller

The Gateway Controller is the RPC entry point that the Client's Zed Stub calls. It extends `AbstractGatewayController` and delegates to the Facade.

```php
// src/SprykerAcademy/Zed/Supplier/Communication/Controller/GatewayController.php
namespace SprykerAcademy\Zed\Supplier\Communication\Controller;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    public function getSuppliersAction(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierCollectionTransfer
    {
        $supplierTransfers = $this->getFacade()->getSuppliers($supplierCriteriaTransfer);

        return (new SupplierCollectionTransfer())
            ->setSuppliers(new \ArrayObject($supplierTransfers));
    }

    public function findSupplierByIdAction(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierTransfer
    {
        $supplierTransfer = $this->getFacade()->findSupplierById(
            $supplierCriteriaTransfer->getIdSupplierOrFail(),
        );

        return $supplierTransfer ?? new SupplierTransfer();
    }
}
```

**Important**: Gateway actions must always return a Transfer object, never `null`. Return an empty transfer when the entity is not found.

### 5. Elasticsearch Publish & Sync Setup

Before the SupplierSearchClient can return data, you need the full publish & sync pipeline.

#### 5a. Search Table Schema

The `pyz_supplier_search` table stores the structured JSON data that gets synced to Elasticsearch. The `synchronization` behavior handles queue-based sync automatically.

```xml
<!-- src/SprykerAcademy/Zed/SupplierSearch/Persistence/Propel/Schema/pyz_supplier_search.schema.xml -->
<table name="pyz_supplier_search" idMethod="native" allowPkInsert="true" identifierQuoting="true">
    <column name="id_supplier_search" type="BIGINT" autoIncrement="true" primaryKey="true"/>
    <column name="fk_supplier" type="INTEGER" required="true"/>
    <index name="pyz_supplier_search-fk_supplier">
        <index-column name="fk_supplier"/>
    </index>

    <behavior name="synchronization">
        <parameter name="resource" value="supplier"/>
        <parameter name="key_suffix_column" value="fk_supplier"/>
        <parameter name="queue_group" value="sync.search.supplier"/>
        <parameter name="params" value='{"type":"supplier"}'/>
    </behavior>

    <behavior name="timestampable"/>
</table>
```

**Key points:**
- `resource="supplier"` — the sync key prefix (e.g., `supplier:1`)
- `params='{"type":"supplier"}'` — tells the sync consumer to write to the **supplier** ES index
- `queue_group="sync.search.supplier"` — the sync queue name (matches `SupplierSearchConfig::SUPPLIER_SYNC_SEARCH_QUEUE`)

#### 5b. SupplierSearchWriter (Data Mapper)

The Writer is responsible for structuring the data correctly for the Elasticsearch supplier index. It uses constants from `SupplierSearchConfig` to avoid magic strings.

**First, ensure you have the Config class with all necessary constants:**

```php
// src/SprykerAcademy/Shared/SupplierSearch/SupplierSearchConfig.php
namespace SprykerAcademy\Shared\SupplierSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SupplierSearchConfig extends AbstractBundleConfig
{
    public const string SUPPLIER_RESOURCE_TYPE = 'supplier';
    public const string SUPPLIER_SOURCE_IDENTIFIER = 'supplier';

    public const string KEY_TYPE = 'type';
    public const string KEY_ID_SUPPLIER = 'id_supplier';
    public const string KEY_NAME = 'name';
    public const string KEY_SEARCH_RESULT_DATA = 'search-result-data';
    public const string KEY_FULL_TEXT = 'full-text';
    public const string KEY_FULL_TEXT_BOOSTED = 'full-text-boosted';
    public const string KEY_SUGGESTION_TERMS = 'suggestion-terms';
    public const string KEY_COMPLETION_TERMS = 'completion-terms';

    // Event constants
    public const string SUPPLIER_PUBLISH = 'SupplierSearch.supplier.publish';
    public const string ENTITY_PYZ_SUPPLIER_CREATE = 'Entity.pyz_supplier.create';
    public const string ENTITY_PYZ_SUPPLIER_UPDATE = 'Entity.pyz_supplier.update';

    // Queue constants
    public const string SUPPLIER_PUBLISH_SEARCH_QUEUE = 'publish.search.supplier';
    public const string SUPPLIER_SYNC_SEARCH_QUEUE = 'sync.search.supplier';
}
```

**Then use these constants in the Writer:**

```php
// src/SprykerAcademy/Zed/SupplierSearch/Business/Writer/SupplierSearchWriter.php (excerpt)
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;

foreach ($supplierTransfersIndexed as $supplierId => $supplierTransfer) {
    $searchData = [
        SupplierSearchConfig::KEY_TYPE => SupplierSearchConfig::SUPPLIER_RESOURCE_TYPE,
        SupplierSearchConfig::KEY_ID_SUPPLIER => $supplierTransfer->getIdSupplier(),
        SupplierSearchConfig::KEY_NAME => $supplierTransfer->getName(),
        SupplierSearchConfig::KEY_SEARCH_RESULT_DATA => $supplierTransfer->toArray(),
        SupplierSearchConfig::KEY_FULL_TEXT => [$supplierTransfer->getName()],
        SupplierSearchConfig::KEY_FULL_TEXT_BOOSTED => [$supplierTransfer->getName()],
        SupplierSearchConfig::KEY_SUGGESTION_TERMS => [$supplierTransfer->getName()],
        SupplierSearchConfig::KEY_COMPLETION_TERMS => [$supplierTransfer->getName()],
    ];

    $supplierSearchTransfer = $supplierSearchTransfersIndexed[$supplierId]
        ?? new SupplierSearchTransfer();

    $supplierSearchTransfer
        ->setFkSupplier($supplierId)
        ->setData($searchData);

    if ($supplierSearchTransfer->getIdSupplierSearch() === null) {
        $this->supplierSearchEntityManager->createSupplierSearch($supplierSearchTransfer);
        continue;
    }

    $this->supplierSearchEntityManager->updateSupplierSearch($supplierSearchTransfer);
}
```

**Key points:**
- Use constants from `SupplierSearchConfig` for all field names and values
- The `params.type = "supplier"` in the schema XML tells the sync consumer which ES **index** to target
- The `KEY_TYPE => SUPPLIER_RESOURCE_TYPE` creates a **field in the ES document**
- The `KEY_SEARCH_RESULT_DATA` key contains the nested supplier data that the ResultFormatter reads

#### 5c. Publisher Write Plugin

Listens to supplier entity events and triggers the Writer. Uses constants for all event names and queue names.

```php
// src/SprykerAcademy/Zed/SupplierSearch/Communication/Plugin/Publisher/SupplierWritePublisherPlugin.php
namespace SprykerAcademy\Zed\SupplierSearch\Communication\Plugin\Publisher;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;

/**
 * @method \SprykerAcademy\Zed\SupplierSearch\Business\SupplierSearchFacadeInterface getFacade()
 */
class SupplierWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionBySupplierEvents($eventEntityTransfers);
    }

    public function getSubscribedEvents(): array
    {
        // Use constants for all event names
        return [
            SupplierSearchConfig::SUPPLIER_PUBLISH,
            SupplierSearchConfig::ENTITY_PYZ_SUPPLIER_CREATE,
            SupplierSearchConfig::ENTITY_PYZ_SUPPLIER_UPDATE,
        ];
    }
}
```

Register in `Pyz\Zed\Publisher\PublisherDependencyProvider`:
```php
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;
use SprykerAcademy\Zed\SupplierSearch\Communication\Plugin\Publisher\SupplierWritePublisherPlugin;

protected function getPublisherPlugins(): array
{
    return [
        // Use SUPPLIER_PUBLISH_SEARCH_QUEUE constant as array key
        SupplierSearchConfig::SUPPLIER_PUBLISH_SEARCH_QUEUE => [
            new SupplierWritePublisherPlugin(),
        ],
    ];
}
```

#### 5d. Publisher Trigger Plugin (for `publish:trigger-events`)

Required to re-publish all supplier data on demand via `publish:trigger-events -r supplier`. Without this plugin, the `-r supplier` resource name is not recognized.

```php
// src/SprykerAcademy/Zed/SupplierSearch/Communication/Plugin/Publisher/SupplierPublisherTriggerPlugin.php
namespace SprykerAcademy\Zed\SupplierSearch\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\Map\PyzSupplierTableMap;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;

class SupplierPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    protected const string COL_ID_SUPPLIER = PyzSupplierTableMap::COL_ID_SUPPLIER;

    public function getData(int $offset, int $limit): array
    {
        $supplierEntities = PyzSupplierQuery::create()
            ->offset($offset)
            ->limit($limit)
            ->find();

        $transfers = [];
        foreach ($supplierEntities as $entity) {
            $transfers[] = (new SupplierTransfer())->fromArray($entity->toArray(), true);
        }

        return $transfers;
    }

    public function getResourceName(): string
    {
        return 'supplier';
    }

    public function getEventName(): string
    {
        return SupplierSearchConfig::SUPPLIER_PUBLISH;
    }

    public function getIdColumnName(): ?string
    {
        return static::COL_ID_SUPPLIER;
    }
}
```

**Key points:**
- `getData()` must return **Transfer objects** (not Propel entities). The publisher calls `modifiedToArray()` on each item, which only exists on `AbstractTransfer`.
- `getIdColumnName()` returns the full Propel column name `pyz_supplier.id_supplier`. The publisher splits on `.` and uses the second part (`id_supplier`) as the array key to extract IDs from `modifiedToArray()` output.
- `getResourceName()` returns the name used with `publish:trigger-events -r supplier`.

Register in `Pyz\Zed\Publisher\PublisherDependencyProvider::getPublisherTriggerPlugins()`:
```php
protected function getPublisherTriggerPlugins(): array
{
    return [
        // ... other trigger plugins
        new SupplierPublisherTriggerPlugin(),
    ];
}
```

#### 5e. Re-publish and sync

```bash
# Trigger publish events for all suppliers (writes structured data to pyz_supplier_search)
docker/sdk cli console publish:trigger-events -r supplier

# Process the sync queue (pushes data from pyz_supplier_search to Elasticsearch)
docker/sdk cli console queue:worker:start --stop-when-empty
```

The data flow:
```
publish:trigger-events -r supplier
    -> SupplierPublisherTriggerPlugin::getData() (loads all suppliers)
    -> SupplierWritePublisherPlugin::handleBulk() (structures data with DataMapper)
    -> pyz_supplier_search.data column (structured JSON with type + search-result-data)
    -> sync.search.supplier queue (SupplierSearchConfig::SUPPLIER_SYNC_SEARCH_QUEUE)
    -> Elasticsearch supplier index (document with type=supplier, search-result-data={...})
```

### 6. SupplierSearch Client Module (Elasticsearch reads)

This module reads supplier data from Elasticsearch. It follows the standard Spryker Search Client pattern: QueryPlugin + ResultFormatterPlugin + Reader.

#### 6a. Query Plugin

The query plugin builds the Elastica query. It uses constants from `SupplierSearchConfig` to ensure consistency across all layers.

```php
// src/SprykerAcademy/Client/SupplierSearch/Plugin/Elasticsearch/Query/SupplierSearchQueryPlugin.php
namespace SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;

class SupplierSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    // Use constants from Config - no magic strings!
    protected const string SOURCE_IDENTIFIER = SupplierSearchConfig::SUPPLIER_SOURCE_IDENTIFIER;
    protected const string RESOURCE_TYPE = SupplierSearchConfig::SUPPLIER_RESOURCE_TYPE;

    protected Query $query {
        get => $field ??= $this->createSearchQuery();
    }

    protected ?SearchContextTransfer $searchContextTransfer = null {
        get => $field ??= new SearchContextTransfer()
            ->setSourceIdentifier(static::SOURCE_IDENTIFIER);
    }

    protected function createSearchQuery(): Query
    {
        $query = new Query();
        $boolQuery = new BoolQuery();

        // Use KEY_TYPE constant for field name
        $boolQuery->addMust(new MatchQuery(SupplierSearchConfig::KEY_TYPE, static::RESOURCE_TYPE));

        $query->setQuery($boolQuery);

        return $query;
    }

    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    public function getSearchContext(): SearchContextTransfer
    {
        return $this->searchContextTransfer;
    }

    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }
}
```

**Key points:**
- `SOURCE_IDENTIFIER = SupplierSearchConfig::SUPPLIER_SOURCE_IDENTIFIER` (value: `'supplier'`) — tells the Search Client which **Elasticsearch index** to query
- `RESOURCE_TYPE = SupplierSearchConfig::SUPPLIER_RESOURCE_TYPE` (value: `'supplier'`) — the document type value for filtering
- `SupplierSearchConfig::KEY_TYPE` — the field name constant (`'type'`)
- Using constants ensures consistency: if you change the value in one place, it updates everywhere

#### 6b. Result Formatter Plugin

Maps Elasticsearch hits to Transfer objects using constants for field names.

```php
// src/SprykerAcademy/Client/SupplierSearch/Plugin/Elasticsearch/ResultFormatter/SupplierSearchResultFormatterPlugin.php
namespace SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;

class SupplierSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    protected const string NAME = 'SupplierSearchCollection';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): SupplierCollectionTransfer
    {
        $supplierCollectionTransfer = new SupplierCollectionTransfer();

        foreach ($searchResult->getResults() as $document) {
            $source = $document->getSource();
            // Use KEY_SEARCH_RESULT_DATA constant
            $data = $source[SupplierSearchConfig::KEY_SEARCH_RESULT_DATA] ?? [];

            $supplierTransfer = (new SupplierTransfer())->fromArray($data, true);
            $supplierCollectionTransfer->addSupplier($supplierTransfer);
        }

        return $supplierCollectionTransfer;
    }
}
```

**Key points:**
- Extends `AbstractElasticsearchResultFormatterPlugin` from `spryker/search-elasticsearch`
- Uses `SupplierSearchConfig::KEY_SEARCH_RESULT_DATA` constant instead of magic string `'search-result-data'`
- Reads from the nested object where the Writer stores supplier fields
- `fromArray($data, true)` — the `true` flag means "ignore missing keys" (no exception if a key doesn't match a transfer property)
- The data in `search-result-data` uses snake_case keys (from `toArray()`), which is the default expected by `fromArray()`

#### 6c. Reader

Orchestrates query expansion, search execution, and result formatting.

```php
// src/SprykerAcademy/Client/SupplierSearch/Reader/SupplierSearchReader.php
namespace SprykerAcademy\Client\SupplierSearch\Reader;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Spryker\Client\Search\SearchClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class SupplierSearchReader implements SupplierSearchReaderInterface
{
    public function __construct(
        protected SearchClientInterface $searchClient,
        protected QueryInterface $supplierSearchQueryPlugin,
        protected array $queryExpanderPlugins,
        protected array $resultFormatterPlugins,
    ) {
    }

    public function searchSuppliers(array $requestParameters = []): SupplierCollectionTransfer
    {
        $searchQuery = $this->searchClient->expandQuery(
            $this->supplierSearchQueryPlugin,
            $this->queryExpanderPlugins,
            $requestParameters,
        );

        $result = $this->searchClient->search(
            $searchQuery,
            $this->resultFormatterPlugins,
            $requestParameters,
        );

        if ($result instanceof SupplierCollectionTransfer) {
            return $result;
        }

        // When result formatters return keyed array
        return $result['SupplierSearchCollection'] ?? new SupplierCollectionTransfer();
    }
}
```

#### 6d. Query Expander Plugins (Optional)

Query expanders modify the Elasticsearch query based on request parameters. They enable features like pagination, sorting, and filtering.

**Common Use Cases:**
- Pagination: Add `from` and `size` to the ES query
- Sorting: Add `sort` clauses based on request parameters
- Filtering: Add additional `must` or `filter` clauses to the BoolQuery

**Example: Pagination Expander**

```php
// src/SprykerAcademy/Client/SupplierSearch/Plugin/Elasticsearch/QueryExpander/PaginationQueryExpanderPlugin.php
namespace SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class PaginationQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    protected const PARAMETER_PAGE = 'page';
    protected const PARAMETER_ITEMS_PER_PAGE = 'ipp';
    protected const DEFAULT_ITEMS_PER_PAGE = 10;

    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $query = $searchQuery->getSearchQuery();

        $page = (int)($requestParameters[static::PARAMETER_PAGE] ?? 1);
        $itemsPerPage = (int)($requestParameters[static::PARAMETER_ITEMS_PER_PAGE] ?? static::DEFAULT_ITEMS_PER_PAGE);

        $from = ($page - 1) * $itemsPerPage;

        $query->setFrom($from);
        $query->setSize($itemsPerPage);

        return $searchQuery;
    }
}
```

**Example: Search by Name Expander**

```php
// src/SprykerAcademy/Client/SupplierSearch/Plugin/Elasticsearch/QueryExpander/SearchByNameQueryExpanderPlugin.php
namespace SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class SearchByNameQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    protected const PARAMETER_SEARCH_STRING = 'q';

    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $query = $searchQuery->getSearchQuery();
        $searchString = $requestParameters[static::PARAMETER_SEARCH_STRING] ?? null;

        if (!$searchString) {
            return $searchQuery;
        }

        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            return $searchQuery;
        }

        // Search in the full-text field
        $boolQuery->addMust(new MatchQuery('full-text', $searchString));

        return $searchQuery;
    }
}
```

Register expanders in `SupplierSearchDependencyProvider`:

```php
$container->set(static::PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER, fn(): array => [
    new PaginationQueryExpanderPlugin(),
    new SearchByNameQueryExpanderPlugin(),
]);
```

**Usage Examples:**

```bash
# Paginated results
curl "http://glue-storefront.eu.spryker.local/suppliers?page=2&ipp=5"

# Search by name
curl "http://glue-storefront.eu.spryker.local/suppliers?q=acme"

# Combined
curl "http://glue-storefront.eu.spryker.local/suppliers?q=acme&page=1&ipp=10"
```

#### 6e. DependencyProvider, Factory, Client

No bridge classes. Provide clients directly from the locator.

```php
// src/SprykerAcademy/Client/SupplierSearch/SupplierSearchDependencyProvider.php
namespace SprykerAcademy\Client\SupplierSearch;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\Query\SupplierSearchQueryPlugin;
use SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\ResultFormatter\SupplierSearchResultFormatterPlugin;

class SupplierSearchDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const PLUGIN_SUPPLIER_SEARCH_QUERY = 'PLUGIN_SUPPLIER_SEARCH_QUERY';
    public const PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER = 'PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER';
    public const PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER = 'PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container->set(static::CLIENT_SEARCH, fn(Container $c) => $c->getLocator()->search()->client());

        $container->set(static::PLUGIN_SUPPLIER_SEARCH_QUERY, fn(): QueryInterface => new SupplierSearchQueryPlugin());

        $container->set(static::PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER, fn(): array => [
            new SupplierSearchResultFormatterPlugin(),
        ]);

        $container->set(static::PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER, fn(): array => []);

        return $container;
    }
}
```

```php
// src/SprykerAcademy/Client/SupplierSearch/SupplierSearchFactory.php
namespace SprykerAcademy\Client\SupplierSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\SearchClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use SprykerAcademy\Client\SupplierSearch\Reader\SupplierSearchReader;
use SprykerAcademy\Client\SupplierSearch\Reader\SupplierSearchReaderInterface;

class SupplierSearchFactory extends AbstractFactory
{
    public function createSupplierSearchReader(): SupplierSearchReaderInterface
    {
        return new SupplierSearchReader(
            $this->getSearchClient(),
            $this->getSupplierSearchQueryPlugin(),
            $this->getSupplierSearchQueryExpanderPlugins(),
            $this->getSupplierSearchResultFormatterPlugins(),
        );
    }

    public function getSearchClient(): SearchClientInterface
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::CLIENT_SEARCH);
    }

    public function getSupplierSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::PLUGIN_SUPPLIER_SEARCH_QUERY);
    }

    public function getSupplierSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER);
    }

    public function getSupplierSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER);
    }
}
```

```php
// src/SprykerAcademy/Client/SupplierSearch/SupplierSearchClient.php
namespace SprykerAcademy\Client\SupplierSearch;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerAcademy\Client\SupplierSearch\SupplierSearchFactory getFactory()
 */
class SupplierSearchClient extends AbstractClient implements SupplierSearchClientInterface
{
    public function searchSuppliers(array $requestParameters = []): SupplierCollectionTransfer
    {
        return $this->getFactory()
            ->createSupplierSearchReader()
            ->searchSuppliers($requestParameters);
    }
}
```

### 7. Supplier Client Module (facade for Glue)

The `SupplierClient` is the single entry point used by the Glue provider. It delegates to `SupplierSearchClient` for collections (Elasticsearch) and to the `SupplierStub` for single-record lookups (ZedRequest RPC).

#### 7a. Zed Stub (for RPC calls)

```php
// src/SprykerAcademy/Client/Supplier/Zed/SupplierStub.php
namespace SprykerAcademy\Client\Supplier\Zed;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class SupplierStub implements SupplierStubInterface
{
    public function __construct(
        protected ZedRequestClientInterface $zedRequestClient,
    ) {
    }

    public function findSupplierById(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierTransfer
    {
        /** @var \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer */
        $supplierTransfer = $this->zedRequestClient->call(
            '/supplier/gateway/find-supplier-by-id',
            $supplierCriteriaTransfer,
        );

        return $supplierTransfer;
    }
}
```

**URL convention**: `/supplier/gateway/find-supplier-by-id` maps to `GatewayController::findSupplierByIdAction()`.

#### 7b. DependencyProvider, Factory, Client

```php
// src/SprykerAcademy/Client/Supplier/SupplierDependencyProvider.php
namespace SprykerAcademy\Client\Supplier;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class SupplierDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_SUPPLIER_SEARCH = 'CLIENT_SUPPLIER_SEARCH';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container->set(static::CLIENT_ZED_REQUEST, fn(Container $c) => $c->getLocator()->zedRequest()->client());
        $container->set(static::CLIENT_SUPPLIER_SEARCH, fn(Container $c) => $c->getLocator()->supplierSearch()->client());

        return $container;
    }
}
```

```php
// src/SprykerAcademy/Client/Supplier/SupplierFactory.php
namespace SprykerAcademy\Client\Supplier;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerAcademy\Client\Supplier\Zed\SupplierStub;
use SprykerAcademy\Client\Supplier\Zed\SupplierStubInterface;
use SprykerAcademy\Client\SupplierSearch\SupplierSearchClientInterface;

class SupplierFactory extends AbstractFactory
{
    public function createSupplierStub(): SupplierStubInterface
    {
        return new SupplierStub($this->getZedRequestClient());
    }

    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(SupplierDependencyProvider::CLIENT_ZED_REQUEST);
    }

    public function getSupplierSearchClient(): SupplierSearchClientInterface
    {
        return $this->getProvidedDependency(SupplierDependencyProvider::CLIENT_SUPPLIER_SEARCH);
    }
}
```

```php
// src/SprykerAcademy/Client/Supplier/SupplierClient.php
namespace SprykerAcademy\Client\Supplier;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerAcademy\Client\Supplier\SupplierFactory getFactory()
 */
class SupplierClient extends AbstractClient implements SupplierClientInterface
{
    // Collection reads from Elasticsearch
    public function getSuppliers(array $requestParameters = []): SupplierCollectionTransfer
    {
        return $this->getFactory()
            ->getSupplierSearchClient()
            ->searchSuppliers($requestParameters);
    }

    // Single record via ZedRequest RPC
    public function findSupplierById(int $idSupplier): SupplierTransfer
    {
        $supplierCriteriaTransfer = (new SupplierCriteriaTransfer())
            ->setIdSupplier($idSupplier);

        return $this->getFactory()
            ->createSupplierStub()
            ->findSupplierById($supplierCriteriaTransfer);
    }
}
```

### 8. API Platform Resource Definition (YAML)

```yaml
# src/SprykerAcademy/Glue/Supplier/resources/api/storefront/suppliers.resource.yml
resource:
    name: Suppliers
    shortName: Supplier
    description: Supplier management API for storefront operations

    provider: SprykerAcademy\Glue\Supplier\Api\Storefront\Provider\SuppliersStorefrontProvider

    paginationEnabled: true
    paginationItemsPerPage: 10

    operations:
        - type: Get
        - type: GetCollection

    properties:
        idSupplier:
            type: int
            description: The unique supplier identifier
            identifier: true    # Required! API Platform uses this to generate IRIs
        name:
            type: string
            description: Supplier name
```

**Important**: One property must have `identifier: true`. Without it, API Platform cannot generate IRIs and will throw "Unable to generate an IRI" errors.

After creating/modifying the YAML, regenerate the API resource class:
```bash
docker/sdk cli console glue api:generate
```

### 9. Glue Storefront Provider

The provider handles incoming API requests. It receives dependencies via constructor injection from Symfony's DI container.

```php
// src/SprykerAcademy/Glue/Supplier/Api/Storefront/Provider/SuppliersStorefrontProvider.php
namespace SprykerAcademy\Glue\Supplier\Api\Storefront\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Generated\Shared\Transfer\SupplierTransfer;
use SprykerAcademy\Client\Supplier\SupplierClientInterface;
use SprykerAcademy\Glue\Supplier\Processor\Mapper\SupplierMapper;

class SuppliersStorefrontProvider implements ProviderInterface
{
    public function __construct(
        protected SupplierClientInterface $supplierClient,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $idSupplier = $uriVariables['idSupplier'] ?? null;

        if ($idSupplier === null) {
            return $this->provideCollection();
        }

        if (!is_numeric($idSupplier)) {
            return null;
        }

        $supplierTransfer = $this->supplierClient->findSupplierById((int)$idSupplier);

        if ($supplierTransfer->getIdSupplier() === null) {
            return null;
        }

        return (new SupplierMapper())->mapSupplierTransferToSuppliersStorefrontResource($supplierTransfer);
    }

    protected function provideCollection(): array
    {
        $supplierCollectionTransfer = $this->supplierClient->getSuppliers();
        $resources = [];
        $mapper = new SupplierMapper();

        foreach ($supplierCollectionTransfer->getSuppliers() as $supplierTransfer) {
            $resources[] = $mapper->mapSupplierTransferToSuppliersStorefrontResource($supplierTransfer);
        }

        return $resources;
    }
}
```

### 10. Glue Mapper

Maps Transfer objects to API Platform resource objects. **Never use Propel entities here.**

```php
// src/SprykerAcademy/Glue/Supplier/Processor/Mapper/SupplierMapper.php
namespace SprykerAcademy\Glue\Supplier\Processor\Mapper;

use Generated\Api\Storefront\SuppliersStorefrontResource;
use Generated\Shared\Transfer\SupplierTransfer;

class SupplierMapper
{
    public function mapSupplierTransferToSuppliersStorefrontResource(
        SupplierTransfer $supplierTransfer,
    ): SuppliersStorefrontResource {
        return SuppliersStorefrontResource::fromArray($supplierTransfer->toArray(false, true));
    }
}
```

**Critical**: Use `toArray(false, true)` — the second parameter `true` produces **camelCased** keys (`idSupplier`). The default `toArray()` produces **snake_cased** keys (`id_supplier`) which won't match the resource class properties.

### 10a. Validation & Error Handling

API Platform automatically handles many validation and error scenarios, but you should add custom validation and meaningful error responses.

#### Input Validation in Provider

```php
// Enhanced provider with validation
public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
{
    $idSupplier = $uriVariables['idSupplier'] ?? null;

    if ($idSupplier === null) {
        return $this->provideCollection();
    }

    // Validate ID format
    if (!is_numeric($idSupplier) || (int)$idSupplier <= 0) {
        throw new \InvalidArgumentException(
            sprintf('Invalid supplier ID: %s. Expected a positive integer.', $idSupplier)
        );
    }

    $supplierTransfer = $this->supplierClient->findSupplierById((int)$idSupplier);

    // Return null for 404 (API Platform handles this)
    if ($supplierTransfer->getIdSupplier() === null) {
        return null;
    }

    return (new SupplierMapper())->mapSupplierTransferToSuppliersStorefrontResource($supplierTransfer);
}
```

#### HTTP Status Codes

API Platform handles common status codes automatically:
- **200 OK** — Successful GET request with data
- **404 Not Found** — Provider returns `null`
- **400 Bad Request** — Invalid input (thrown exceptions are caught)
- **500 Internal Server Error** — Unhandled exceptions

#### Custom Error Messages

For more control over error responses, you can throw specific exceptions:

```php
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

// In provider:
if (!is_numeric($idSupplier)) {
    throw new BadRequestHttpException('Supplier ID must be a valid integer');
}

if ($supplierTransfer->getIdSupplier() === null) {
    throw new NotFoundHttpException(sprintf('Supplier with ID %d not found', $idSupplier));
}
```

### 11. Register Services in ApplicationServices.php

The Glue Storefront uses Symfony DI. You must register your Client in `config/GlueStorefront/ApplicationServices.php`.

```php
// config/GlueStorefront/ApplicationServices.php
use SprykerAcademy\Client\Supplier\SupplierClient;
use SprykerAcademy\Client\Supplier\SupplierClientInterface;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()->autowire()->public()->autoconfigure();

    $services->set(ProxyFactory::class)->public();
    $services->set(SupplierClientInterface::class, SupplierClient::class);
};
```

**Note**: Only register the Client interface/class. The Spryker kernel auto-resolves the Client's Factory and DependencyProvider internally. Mappers and other simple classes should be instantiated directly (e.g., `new SupplierMapper()`) rather than registered in the container.

### 12. API Platform Config

Ensure `config/GlueStorefront/packages/spryker_api_platform.php` includes your source directories:

```php
$sprykerApiPlatform->sourceDirectories([
    'src/Pyz',
    'src/SprykerAcademy',   // <-- your namespace must be listed
    'vendor/spryker',
    'vendor/spryker-shop',
    'vendor/spryker-feature',
]);
```

### 13. Build and Test

```bash
# Generate transfers (SupplierCollectionTransfer, etc.)
docker/sdk cli console transfer:generate

# Generate API Platform resource classes from YAML
docker/sdk cli console glue api:generate

# Re-publish supplier data (writes structured data to pyz_supplier_search)
docker/sdk cli console publish:trigger-events -r supplier

# Process the sync queue (pushes data to Elasticsearch)
docker/sdk cli console queue:worker:start --stop-when-empty

# Clear all caches (routing, DI container, etc.)
docker/sdk cli console cache:empty-all

# Test collection (reads from Elasticsearch)
curl http://glue-storefront.eu.spryker.local/suppliers

# Test single record (reads via ZedRequest RPC)
curl http://glue-storefront.eu.spryker.local/suppliers/1
```

### 13a. Additional Useful Commands

```bash
# Publish a single supplier by ID
docker/sdk cli console publish:trigger-events -r supplier -i 1

# Check Elasticsearch mapping for supplier index
curl -X GET "http://localhost:10005/de_supplier/_mapping?pretty"

# List all Elasticsearch indices
curl -X GET "http://localhost:10005/_cat/indices?v"

# Manually trigger entity event
docker/sdk cli console event:trigger pyz_supplier.entity.create -i 1

# Rebuild Propel models after schema changes
docker/sdk cli console propel:model:build
docker/sdk cli console propel:diff
docker/sdk cli console propel:migrate

# View specific queue messages
docker/sdk cli console queue:task:start sync.search.supplier -s 1 -l 5

# Clear specific cache type
docker/sdk cli console cache:clear-storefront-resources
docker/sdk cli console router:cache:clear

# Debug route generation
docker/sdk cli console debug:router --show-controllers | grep -i supplier

# Validate API Platform configuration
docker/sdk cli console api:platform:validate

# Pretty-print JSON responses
curl http://glue-storefront.eu.spryker.local/suppliers/1 | jq

# Test with headers
curl -H "Accept: application/json" \
     -H "Content-Type: application/json" \
     http://glue-storefront.eu.spryker.local/suppliers

# Benchmark API response time
curl -w "@-" -o /dev/null -s http://glue-storefront.eu.spryker.local/suppliers <<'EOF'
    time_namelookup:  %{time_namelookup}\n
       time_connect:  %{time_connect}\n
    time_appconnect:  %{time_appconnect}\n
      time_redirect:  %{time_redirect}\n
   time_starttransfer:  %{time_starttransfer}\n
                     ----------\n
         time_total:  %{time_total}\n
EOF
```

### 14. Testing

#### Unit Tests

**Testing the Repository:**

```php
// tests/SprykerAcademyTest/Zed/Supplier/Business/SupplierFacadeTest.php
namespace SprykerAcademyTest\Zed\Supplier\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SupplierCriteriaTransfer;

class SupplierFacadeTest extends Unit
{
    protected $tester;

    public function testFindSupplierByIdReturnsSupplier(): void
    {
        // Arrange
        $supplierTransfer = $this->tester->haveSupplier([
            'name' => 'Test Supplier',
            'email' => 'test@supplier.com',
        ]);

        // Act
        $resultTransfer = $this->tester->getFacade()->findSupplierById(
            $supplierTransfer->getIdSupplier()
        );

        // Assert
        $this->assertNotNull($resultTransfer);
        $this->assertEquals('Test Supplier', $resultTransfer->getName());
    }

    public function testFindSupplierByIdReturnsNullForNonExistent(): void
    {
        // Act
        $resultTransfer = $this->tester->getFacade()->findSupplierById(99999);

        // Assert
        $this->assertNull($resultTransfer);
    }
}
```

**Testing the Search Writer:**

```php
// tests/SprykerAcademyTest/Zed/SupplierSearch/Business/SupplierSearchFacadeTest.php
namespace SprykerAcademyTest\Zed\SupplierSearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;

class SupplierSearchFacadeTest extends Unit
{
    protected $tester;

    public function testWriteCollectionCreatesSearchEntity(): void
    {
        // Arrange
        $supplierTransfer = $this->tester->haveSupplier([
            'name' => 'Search Test Supplier',
        ]);

        $eventEntityTransfer = (new EventEntityTransfer())
            ->setId($supplierTransfer->getIdSupplier());

        // Act
        $this->tester->getFacade()->writeCollectionBySupplierEvents([$eventEntityTransfer]);

        // Assert
        $searchEntity = $this->tester->findSupplierSearchEntityByFkSupplier(
            $supplierTransfer->getIdSupplier()
        );

        $this->assertNotNull($searchEntity);
        $data = $searchEntity->getData();
        $this->assertEquals('supplier', $data['type']);
        $this->assertArrayHasKey('search-result-data', $data);
    }
}
```

#### API Integration Tests

```php
// tests/SprykerAcademyTest/Glue/Supplier/RestApi/SuppliersRestApiTest.php
namespace SprykerAcademyTest\Glue\Supplier\RestApi;

use SprykerAcademyTest\Glue\Supplier\SuppliersApiTester;

class SuppliersRestApiTest extends \Codeception\Test\Unit
{
    protected SuppliersApiTester $tester;

    public function testGetSupplierCollection(): void
    {
        // Arrange
        $this->tester->haveSupplierInElasticsearch([
            'idSupplier' => 1,
            'name' => 'API Test Supplier',
        ]);

        // Act
        $this->tester->sendGet('/suppliers');

        // Assert
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();
        $this->tester->seeResponseContainsJson([
            'data' => [
                [
                    'type' => 'suppliers',
                    'attributes' => [
                        'name' => 'API Test Supplier',
                    ],
                ],
            ],
        ]);
    }

    public function testGetSupplierById(): void
    {
        // Arrange
        $supplierTransfer = $this->tester->haveSupplier([
            'name' => 'Single Supplier Test',
        ]);

        // Act
        $this->tester->sendGet('/suppliers/' . $supplierTransfer->getIdSupplier());

        // Assert
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseMatchesJsonType([
            'data' => [
                'type' => 'string',
                'id' => 'string',
                'attributes' => [
                    'name' => 'string',
                ],
            ],
        ]);
    }

    public function testGetNonExistentSupplierReturns404(): void
    {
        // Act
        $this->tester->sendGet('/suppliers/99999');

        // Assert
        $this->tester->seeResponseCodeIs(404);
    }
}
```

#### Running Tests

```bash
# Run all tests
docker/sdk cli vendor/bin/codecept run

# Run specific test suite
docker/sdk cli vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/Supplier

# Run with coverage
docker/sdk cli vendor/bin/codecept run --coverage --coverage-html

# Run a specific test
docker/sdk cli vendor/bin/codecept run tests/SprykerAcademyTest/Zed/Supplier/Business/SupplierFacadeTest.php
```

## Performance Optimization

### Elasticsearch Performance

#### 1. Optimize Index Mapping

Define explicit mappings for better performance and storage:

```json
{
  "mappings": {
    "properties": {
      "type": {
        "type": "keyword"
      },
      "search-result-data": {
        "type": "object",
        "enabled": true
      },
      "full-text": {
        "type": "text",
        "analyzer": "standard"
      },
      "full-text-boosted": {
        "type": "text",
        "analyzer": "standard",
        "boost": 2.0
      }
    }
  }
}
```

#### 2. Use Pagination

Always paginate large result sets:

```php
// In QueryExpanderPlugin
$query->setFrom(($page - 1) * $itemsPerPage);
$query->setSize($itemsPerPage);
```

#### 3. Limit Returned Fields

Only fetch fields you need:

```php
$query->setSource(['search-result-data.idSupplier', 'search-result-data.name']);
```

#### 4. Use Filters Instead of Queries

Filters are cached and faster for exact matches:

```php
// Good - uses filter (cached)
$boolQuery->addFilter(new TermQuery('type', 'supplier'));

// Slower - uses query (scored)
$boolQuery->addMust(new MatchQuery('type', 'supplier'));
```

### ZedRequest RPC Performance

#### 1. Cache Frequently Accessed Data

```php
// In SupplierClient
public function findSupplierById(int $idSupplier): SupplierTransfer
{
    $cacheKey = sprintf('supplier:%d', $idSupplier);

    // Try cache first
    $cachedData = $this->getFactory()->getCacheClient()->get($cacheKey);
    if ($cachedData) {
        return (new SupplierTransfer())->fromArray($cachedData, true);
    }

    // Fetch from Zed
    $supplierTransfer = $this->getFactory()
        ->createSupplierStub()
        ->findSupplierById($criteriaTransfer);

    // Cache for 5 minutes
    if ($supplierTransfer->getIdSupplier()) {
        $this->getFactory()->getCacheClient()->set(
            $cacheKey,
            $supplierTransfer->toArray(),
            300
        );
    }

    return $supplierTransfer;
}
```

#### 2. Batch RPC Calls

Instead of multiple single-record calls, batch them:

```php
// Bad - N+1 problem
foreach ($supplierIds as $id) {
    $suppliers[] = $this->supplierClient->findSupplierById($id);
}

// Good - single batch call
$criteriaTransfer = (new SupplierCriteriaTransfer())
    ->setSupplierIds($supplierIds);
$supplierCollection = $this->supplierClient->getSuppliersByIds($criteriaTransfer);
```

### API Response Optimization

#### 1. Enable HTTP Caching

Add cache headers to responses:

```php
// In provider or via API Platform configuration
$response->setCache([
    'max_age' => 600,
    'public' => true,
]);
```

#### 2. Use ETags

API Platform supports ETags out of the box for conditional requests.

#### 3. Compress Responses

Ensure gzip compression is enabled in nginx/Apache configuration.

### Database Query Optimization

#### 1. Add Indexes

```xml
<!-- In schema.xml -->
<table name="pyz_supplier">
    <index name="idx_supplier_status">
        <index-column name="status"/>
    </index>
    <index name="idx_supplier_name">
        <index-column name="name"/>
    </index>
</table>
```

#### 2. Use Propel Query Optimization

```php
// Select only needed columns
$suppliers = PyzSupplierQuery::create()
    ->select(['id_supplier', 'name', 'email'])
    ->find();

// Use joins efficiently
$suppliers = PyzSupplierQuery::create()
    ->leftJoinWith('SpySupplierLocation')
    ->find();
```

### Monitoring Performance

#### Track Response Times

```php
// Add timing to provider
$startTime = microtime(true);

$result = $this->supplierClient->getSuppliers();

$duration = (microtime(true) - $startTime) * 1000;
error_log(sprintf('Supplier collection fetch took %.2f ms', $duration));
```

#### Use Spryker Monitoring

```bash
# Enable NewRelic or other APM tools in config
docker/sdk cli console monitoring:report
```

## Security & CORS Configuration

### CORS (Cross-Origin Resource Sharing)

If your API will be consumed by a frontend application on a different domain, you need to configure CORS.

#### Enable CORS in Glue Storefront

Add to `config/Shared/config_default.php`:

```php
use Spryker\Shared\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConstants;

// Allow all origins (development only!)
$config[GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_CORS_ALLOW_ORIGIN] = '*';

// Or specify allowed origins (recommended for production)
$config[GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_CORS_ALLOW_ORIGIN] = 'https://your-frontend-domain.com';

// Configure allowed headers
$config[GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_CORS_ALLOW_HEADERS] = [
    'Content-Type',
    'Accept',
    'Authorization',
];

// Configure allowed methods
$config[GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_CORS_ALLOW_METHODS] = [
    'GET',
    'POST',
    'PUT',
    'PATCH',
    'DELETE',
    'OPTIONS',
];

// Allow credentials
$config[GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_CORS_ALLOW_CREDENTIALS] = true;
```

### Authentication & Authorization

For protected endpoints, implement authentication using Spryker's built-in mechanisms:

#### Customer Authentication Example

```php
// In your provider, inject the customer client
public function __construct(
    protected SupplierClientInterface $supplierClient,
    protected CustomerClientInterface $customerClient,
) {
}

public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
{
    // Check if customer is authenticated (if required)
    $customerTransfer = $this->customerClient->getCustomer();
    if (!$customerTransfer) {
        throw new UnauthorizedHttpException('Bearer', 'Authentication required');
    }

    // Your normal logic...
}
```

### Rate Limiting

Consider implementing rate limiting to prevent abuse:

```php
// Add to config/Shared/config_default.php
use Spryker\Shared\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConstants;

$config[GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_RATE_LIMIT_ENABLED] = true;
$config[GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_RATE_LIMIT_REQUESTS] = 100; // requests
$config[GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_RATE_LIMIT_PERIOD] = 60; // seconds
```

### Security Best Practices

1. **Never expose sensitive data**: Filter out internal IDs, passwords, tokens in API responses
2. **Validate all inputs**: Check types, ranges, and formats
3. **Use HTTPS**: Always use SSL/TLS in production
4. **Implement proper error handling**: Don't leak stack traces or internal details
5. **Log security events**: Track authentication failures, suspicious activity
6. **Keep dependencies updated**: Regularly update Spryker and dependencies

## API Versioning & Documentation

### API Versioning Strategy

While API Platform supports versioning, Spryker's approach typically uses backward-compatible changes. For breaking changes, consider:

#### Option 1: URL Path Versioning

```yaml
# suppliers.resource.yml (v1)
resource:
    name: SuppliersV1
    routePrefix: /v1
    # ...

# suppliers-v2.resource.yml (v2)
resource:
    name: SuppliersV2
    routePrefix: /v2
    # ...
```

Usage:
```bash
curl http://glue-storefront.eu.spryker.local/v1/suppliers
curl http://glue-storefront.eu.spryker.local/v2/suppliers
```

#### Option 2: Header-Based Versioning

```php
// In provider
public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
{
    $apiVersion = $context['request']->headers->get('API-Version', '1.0');

    if ($apiVersion === '2.0') {
        return $this->provideV2($uriVariables, $context);
    }

    return $this->provideV1($uriVariables, $context);
}
```

### OpenAPI Documentation

API Platform automatically generates OpenAPI (Swagger) documentation.

#### Access Documentation

```bash
# JSON format
curl http://glue-storefront.eu.spryker.local/docs.json

# JSONLD format
curl http://glue-storefront.eu.spryker.local/docs.jsonld
```

#### Enhance Documentation in YAML

```yaml
resource:
    name: Suppliers
    shortName: Supplier
    description: |
        Supplier management API for storefront operations.

        Suppliers represent organizations that provide products to the system.
        This API allows retrieving supplier information including contact details
        and operational status.

    operations:
        - type: Get
          description: Retrieve a single supplier by ID
        - type: GetCollection
          description: Retrieve a paginated list of all suppliers

    properties:
        idSupplier:
            type: int
            description: The unique supplier identifier
            identifier: true
            example: 42
        name:
            type: string
            description: The official registered name of the supplier
            example: "ACME Corporation"
        email:
            type: string
            description: Primary contact email address
            example: "contact@acme.com"
        status:
            type: int
            description: Supplier operational status (1=active, 0=inactive)
            example: 1
```

#### Custom Documentation Annotations

For more control, use PHP attributes:

```php
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Supplier resource with enhanced documentation',
    paginationEnabled: true,
    paginationItemsPerPage: 10,
)]
class SuppliersStorefrontResource
{
    #[ApiProperty(
        description: 'Unique identifier for the supplier',
        identifier: true,
        example: 42
    )]
    public int $idSupplier;

    #[ApiProperty(
        description: 'Supplier legal business name',
        required: true,
        example: 'ACME Corporation'
    )]
    public string $name;
}
```

### API Documentation Best Practices

1. **Provide examples** for all properties
2. **Document error responses** with expected status codes
3. **Explain rate limits** and authentication requirements
4. **Include usage examples** with curl commands
5. **Version your documentation** alongside your API
6. **Keep it up-to-date** - regenerate after any YAML changes

### Generate Static Documentation

```bash
# Generate OpenAPI spec file
docker/sdk cli console api:openapi:export > openapi.json

# Use tools like Redoc or Swagger UI to generate HTML docs
docker run -p 8080:80 -e SPEC_URL=openapi.json redocly/redoc
```

## Debugging Guide

When things don't work as expected, follow these debugging steps:

### Check if Data is in Elasticsearch

```bash
# Check if the supplier index exists
curl -X GET "http://localhost:10005/_cat/indices?v" | grep supplier

# Query all supplier documents
curl -X GET "http://localhost:10005/de_supplier/_search?pretty" -H 'Content-Type: application/json' -d'
{
  "query": {
    "match_all": {}
  }
}
'

# Query supplier documents with type filter
curl -X GET "http://localhost:10005/de_supplier/_search?pretty" -H 'Content-Type: application/json' -d'
{
  "query": {
    "match": {
      "type": "supplier"
    }
  }
}
'

# Check a specific supplier document by its sync key
curl -X GET "http://localhost:10005/de_supplier/_doc/supplier:1?pretty"

# Count supplier documents
curl -X GET "http://localhost:10005/de_supplier/_count?pretty" -H 'Content-Type: application/json' -d'
{
  "query": {
    "match_all": {}
  }
}
'

# View the index mapping
curl -X GET "http://localhost:10005/de_supplier/_mapping?pretty"
```

**Note**: Replace `10005` with your Elasticsearch port and `de_supplier` with your store's supplier index name (format: `{store}_supplier`).

### Check Sync Queue Status

```bash
# View messages in the sync queue without consuming them
docker/sdk cli console queue:task:start sync.search.supplier -s 1 -l 10

# Check queue statistics
docker/sdk cli console queue:queue:list
```

### Check Database Tables

```bash
# Verify data in pyz_supplier_search table
docker/sdk cli console propel:sql:execute "SELECT id_supplier_search, fk_supplier, data FROM pyz_supplier_search LIMIT 5"

# Check synchronization columns
docker/sdk cli console propel:sql:execute "SELECT fk_supplier, synchronization_key, synchronization_data FROM pyz_supplier_search WHERE fk_supplier = 1"
```

### Debug ZedRequest RPC Calls

Add temporary logging in the Gateway Controller:

```php
public function findSupplierByIdAction(SupplierCriteriaTransfer $supplierCriteriaTransfer): SupplierTransfer
{
    error_log('Gateway called with ID: ' . $supplierCriteriaTransfer->getIdSupplier());

    $supplierTransfer = $this->getFacade()->findSupplierById(
        $supplierCriteriaTransfer->getIdSupplierOrFail(),
    );

    error_log('Gateway returning: ' . ($supplierTransfer ? 'found' : 'null'));

    return $supplierTransfer ?? new SupplierTransfer();
}
```

Check logs:
```bash
docker/sdk cli tail -f /data/logs/ZED/application.log
```

### Debug Elasticsearch Query

Add logging to your QueryPlugin:

```php
public function getSearchQuery(): Query
{
    $queryArray = $this->query->toArray();
    error_log('ES Query: ' . json_encode($queryArray, JSON_PRETTY_PRINT));

    return $this->query;
}
```

Check logs:
```bash
docker/sdk cli tail -f /data/logs/GLUE-STOREFRONT/application.log
```

### Verify API Platform Route Registration

```bash
# List all registered API routes
docker/sdk cli console debug:router | grep supplier

# Clear routing cache
docker/sdk cli console cache:clear
docker/sdk cli console router:cache:clear
```

### Common Debug Commands

```bash
# Check if publisher events are being triggered
docker/sdk cli console publish:trigger-events -r supplier -i 1

# Manually publish a single supplier
docker/sdk cli console event:trigger pyz_supplier.entity.create -i 1

# Check queue worker status
docker/sdk cli console queue:worker:start --stop-when-empty -vvv

# Rebuild all search tables
docker/sdk cli console propel:schema:copy
docker/sdk cli console propel:model:build
docker/sdk cli console propel:migrate
```

### Log File Locations

```
/data/logs/ZED/application.log              - Zed application logs
/data/logs/GLUE-STOREFRONT/application.log  - Glue Storefront logs
/data/logs/APPLICATION/exception.log        - Exception logs
/data/logs/QUEUE/queue.log                  - Queue worker logs
```

## Common Pitfalls

| Error | Cause | Fix |
|---|---|---|
| `Database map was not initialized` | Using Propel/Facade directly in Glue | Use a Client module with ZedRequest RPC or Elasticsearch |
| `Unable to generate an IRI` | Resource identifier property is null | Ensure `toArray(false, true)` for camelCased keys; ensure a property has `identifier: true` in YAML |
| `Could not find "X" in any of the attached containers` | Class not registered in Symfony DI or not auto-resolvable | Register in `ApplicationServices.php` or instantiate directly |
| `Class not found` for generated resource | API resource class not generated after YAML change | Run `docker/sdk cli console glue api:generate` |
| 404 Not Found | Route cache is stale | Run `docker/sdk cli console cache:empty-all` |
| Empty response data from ZedRequest | Returning arrays instead of Collection transfer | Wrap arrays in a Collection transfer (`SupplierCollectionTransfer`) |
| Empty collection from Elasticsearch | Data not synced, wrong `SOURCE_IDENTIFIER`, or missing DataMapper | Run `publish:trigger-events -r supplier` then `queue:worker:start --stop-when-empty`; verify `SOURCE_IDENTIFIER = SupplierSearchConfig::SUPPLIER_SOURCE_IDENTIFIER` |
| Index not found error from ES | Wrong index name in QueryPlugin or schema | Ensure `params='{"type":"supplier"}'` in schema matches `SOURCE_IDENTIFIER = 'supplier'` in QueryPlugin |
| ES returns hits but transfers are empty | Data stored flat instead of under `search-result-data` | Ensure `SupplierSearchWriter` structures data with `SupplierSearchConfig::KEY_SEARCH_RESULT_DATA => $transfer->toArray()` then re-publish |
| Magic strings causing inconsistencies | Hardcoded strings like 'supplier', 'type', 'search-result-data' | Use constants from `SupplierSearchConfig` for all field names, index names, and queue names |
| `There is no resource with the name: supplier` on `publish:trigger-events` | Missing `PublisherTriggerPluginInterface` implementation | Create `SupplierPublisherTriggerPlugin` and register in `PublisherDependencyProvider::getPublisherTriggerPlugins()` |
| `Call to undefined method: modifiedToArray` on trigger | `getData()` returns Propel entities instead of Transfer objects | Return `SupplierTransfer` objects from `getData()`, not raw Propel entities |
| `Undefined array key "id_supplier"` on trigger | `getData()` returns `EventEntityTransfer` (has `id` key, not `id_supplier`) | Return domain Transfer objects (e.g., `SupplierTransfer`) whose `modifiedToArray()` contains the key matching `getIdColumnName()` |
| CORS errors in browser | CORS not configured for Glue Storefront | Add CORS configuration to `config_default.php` with allowed origins, headers, and methods |
| Slow API responses | No pagination, inefficient ES queries, or N+1 RPC calls | Add pagination, use filters instead of queries, implement caching, batch RPC calls |
| Missing API documentation | OpenAPI not regenerated after YAML changes | Run `docker/sdk cli console api:openapi:export` and enhance YAML with descriptions and examples |
| Query expander not working | Expander not registered in DependencyProvider | Add expander to `PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER` array in `SupplierSearchDependencyProvider` |

## File Structure Reference

```
src/SprykerAcademy/
  Shared/
    Supplier/Transfer/supplier.transfer.xml
    SupplierSearch/
      Transfer/supplier_search.transfer.xml
      Schema/supplier.json                        # ES index mapping
      SupplierSearchConfig.php                    # Event constants

  Zed/Supplier/
    Business/
      Reader/SupplierReader.php
      SupplierBusinessFactory.php
      SupplierFacade.php
      SupplierFacadeInterface.php
    Communication/Controller/
      GatewayController.php                       # RPC entry point
    Persistence/
      SupplierRepository.php
      SupplierPersistenceFactory.php
      Propel/Mapper/SupplierMapper.php

  Zed/SupplierSearch/
    Business/Writer/SupplierSearchWriter.php      # Structures data for ES (DataMapper)
    Persistence/
      SupplierSearchEntityManager.php
      SupplierSearchRepository.php
      Propel/Schema/pyz_supplier_search.schema.xml
    Communication/Plugin/Publisher/
      SupplierWritePublisherPlugin.php            # Handles entity events
      SupplierPublisherTriggerPlugin.php          # Enables publish:trigger-events -r supplier

  Client/SupplierSearch/                          # Reads from Elasticsearch
    Plugin/Elasticsearch/
      Query/SupplierSearchQueryPlugin.php         # Elastica query (page index, type=supplier)
      QueryExpander/
        PaginationQueryExpanderPlugin.php         # Adds pagination to ES query
        SearchByNameQueryExpanderPlugin.php       # Adds name search filter
      ResultFormatter/
        SupplierSearchResultFormatterPlugin.php   # ES hits -> Transfers
    Reader/
      SupplierSearchReader.php                    # Orchestrates search
      SupplierSearchReaderInterface.php
    SupplierSearchClient.php
    SupplierSearchClientInterface.php
    SupplierSearchFactory.php
    SupplierSearchDependencyProvider.php

  Client/Supplier/                                # Facade for Glue
    Zed/
      SupplierStub.php                            # RPC calls to Gateway
      SupplierStubInterface.php
    SupplierClient.php                            # getSuppliers() -> ES, findById() -> RPC
    SupplierClientInterface.php
    SupplierFactory.php
    SupplierDependencyProvider.php

  Glue/Supplier/
    Api/Storefront/Provider/
      SuppliersStorefrontProvider.php             # Handles API requests
    Processor/Mapper/
      SupplierMapper.php                          # Transfer -> Resource
    resources/api/storefront/
      suppliers.resource.yml                      # API Platform definition

config/GlueStorefront/
  ApplicationServices.php                         # Symfony DI registration
  packages/spryker_api_platform.php               # Source directories

Pyz/Zed/Publisher/
  PublisherDependencyProvider.php                  # Register Write + Trigger plugins

tests/SprykerAcademyTest/
  Zed/Supplier/
    Business/SupplierFacadeTest.php               # Unit tests for Facade
  Zed/SupplierSearch/
    Business/SupplierSearchFacadeTest.php         # Unit tests for Search Writer
  Glue/Supplier/
    RestApi/SuppliersRestApiTest.php              # API integration tests
    SuppliersApiTester.php                        # Test helper
```
