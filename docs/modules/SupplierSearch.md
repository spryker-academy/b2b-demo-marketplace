# SupplierSearch Module

## Overview

The **SupplierSearch** module handles the synchronization of supplier data to Elasticsearch and provides search functionality for the Glue API. It implements Spryker's Publish & Sync pattern for denormalized search data.

## Purpose

- Publish supplier data changes to Elasticsearch
- Provide fast search capabilities for Glue API
- Denormalize data for optimal search performance
- Handle full-text search, filtering, and pagination

## Architecture Overview

```
Supplier Entity Change
    ↓
Publisher Plugin (listens to events)
    ↓
SupplierSearchWriter (structures data)
    ↓
pyz_supplier_search table (sync data)
    ↓
Sync Queue (sync.search.supplier)
    ↓
Elasticsearch (supplier index)
    ↓
SupplierSearchClient (queries ES)
    ↓
Glue API
```

---

## Shared Layer

### Configuration

**File**: `Shared/SupplierSearch/SupplierSearchConfig.php`

**Purpose**: Central configuration constants for all layers.

```php
class SupplierSearchConfig extends AbstractBundleConfig
{
    // Resource and index identifiers
    public const string SUPPLIER_RESOURCE_TYPE = 'supplier';
    public const string SUPPLIER_SOURCE_IDENTIFIER = 'supplier';

    // Queue names
    public const string SUPPLIER_PUBLISH_SEARCH_QUEUE = 'publish.search.supplier';
    public const string SUPPLIER_SYNC_SEARCH_QUEUE = 'sync.search.supplier';

    // Event names
    public const string SUPPLIER_PUBLISH = 'SupplierSearch.supplier.publish';
    public const string ENTITY_PYZ_SUPPLIER_CREATE = 'Entity.pyz_supplier.create';
    public const string ENTITY_PYZ_SUPPLIER_UPDATE = 'Entity.pyz_supplier.update';

    // Field name constants (ES document structure)
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

**Best Practices**:
- Define ALL constants here (no magic strings)
- Use typed constants (`const string`, `const int`)
- Document each constant's purpose
- Group related constants together

---

## Zed Layer (Publish Pipeline)

### 1. Database Schema

**File**: `Zed/SupplierSearch/Persistence/Propel/Schema/pyz_supplier_search.schema.xml`

```xml
<table name="pyz_supplier_search">
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

**Synchronization Behavior Parameters**:
- `resource`: Sync key prefix (e.g., `supplier:1`)
- `key_suffix_column`: Column used for key suffix
- `queue_group`: Queue name for sync messages
- `params.type`: Elasticsearch index name

**Best Practices**:
- Always add index on foreign key
- Use synchronization behavior for auto-sync
- Match queue_group with Config constant
- Add timestampable for audit trail

### 2. SupplierSearchWriter (Data Mapper)

**File**: `Zed/SupplierSearch/Business/Writer/SupplierSearchWriter.php`

**Purpose**: Structures supplier data for Elasticsearch with proper search fields.

```php
class SupplierSearchWriter
{
    public function writeCollectionBySupplierEvents(array $eventTransfers): void
    {
        $supplierIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $this->writeCollectionBySupplierIds($supplierIds);
    }

    protected function writeCollectionBySupplierIds(array $supplierIds): void
    {
        $supplierTransfers = $this->getSupplierTransfersIndexed($supplierIds);
        $searchTransfers = $this->getSupplierSearchTransfersIndexed($supplierIds);

        foreach ($supplierTransfers as $supplierId => $supplierTransfer) {
            // Structure data for ES
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

            $searchTransfer = $searchTransfers[$supplierId] ?? new SupplierSearchTransfer();
            $searchTransfer
                ->setFkSupplier($supplierId)
                ->setData($searchData);

            // Create or update
            if ($searchTransfer->getIdSupplierSearch() === null) {
                $this->entityManager->createSupplierSearch($searchTransfer);
            } else {
                $this->entityManager->updateSupplierSearch($searchTransfer);
            }
        }
    }
}
```

**Document Structure Explanation**:
- `type`: Document type field (value: 'supplier') - used for filtering
- `id_supplier`: Direct access to ID
- `name`: Direct access to name
- `search-result-data`: NESTED object with full supplier data (what ResultFormatter reads)
- `full-text`: Array of searchable text fields
- `full-text-boosted`: Important searchable fields (higher relevance score)
- `suggestion-terms`: For autocomplete suggestions
- `completion-terms`: For search-as-you-type

**Best Practices**:
- Use constants for ALL field names
- Structure data with `search-result-data` key
- Include searchable text in `full-text` arrays
- Handle create vs update logic
- Load existing search records to preserve IDs

### 3. Publisher Write Plugin

**File**: `Zed/SupplierSearch/Communication/Plugin/Publisher/SupplierWritePublisherPlugin.php`

**Purpose**: Listens to supplier entity events and triggers the Writer.

```php
class SupplierWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionBySupplierEvents($eventEntityTransfers);
    }

    public function getSubscribedEvents(): array
    {
        return [
            SupplierSearchConfig::SUPPLIER_PUBLISH,
            SupplierSearchConfig::ENTITY_PYZ_SUPPLIER_CREATE,
            SupplierSearchConfig::ENTITY_PYZ_SUPPLIER_UPDATE,
        ];
    }
}
```

**Subscribed Events**:
- `SUPPLIER_PUBLISH`: Manual publish trigger
- `ENTITY_PYZ_SUPPLIER_CREATE`: Automatic on INSERT
- `ENTITY_PYZ_SUPPLIER_UPDATE`: Automatic on UPDATE

**Best Practices**:
- Use constants for event names
- Handle bulk events (not one-by-one)
- Keep plugin thin - delegate to Facade
- Subscribe to all relevant entity events

**Registration**:
```php
// In Pyz/Zed/Publisher/PublisherDependencyProvider.php
protected function getPublisherPlugins(): array
{
    return [
        SupplierSearchConfig::SUPPLIER_PUBLISH_SEARCH_QUEUE => [
            new SupplierWritePublisherPlugin(),
        ],
    ];
}
```

### 4. Publisher Trigger Plugin

**File**: `Zed/SupplierSearch/Communication/Plugin/Publisher/SupplierPublisherTriggerPlugin.php`

**Purpose**: Enables bulk publishing via `publish:trigger-events -r supplier`.

```php
class SupplierPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    protected const string COL_ID_SUPPLIER = PyzSupplierTableMap::COL_ID_SUPPLIER;

    public function getData(int $offset, int $limit): array
    {
        $entities = PyzSupplierQuery::create()
            ->offset($offset)
            ->limit($limit)
            ->find();

        $transfers = [];
        foreach ($entities as $entity) {
            $transfers[] = (new SupplierTransfer())->fromArray($entity->toArray(), true);
        }

        return $transfers; // MUST return Transfer objects, NOT entities
    }

    public function getResourceName(): string
    {
        return 'supplier'; // Used in: publish:trigger-events -r supplier
    }

    public function getEventName(): string
    {
        return SupplierSearchConfig::SUPPLIER_PUBLISH;
    }

    public function getIdColumnName(): ?string
    {
        return static::COL_ID_SUPPLIER; // Full column name: pyz_supplier.id_supplier
    }
}
```

**Critical Points**:
- `getData()` MUST return **Transfer objects**, NOT Propel entities
- `getIdColumnName()` returns full column name (e.g., `pyz_supplier.id_supplier`)
- The publisher splits this on `.` to extract the field name for `modifiedToArray()`

**Registration**:
```php
// In Pyz/Zed/Publisher/PublisherDependencyProvider.php
protected function getPublisherTriggerPlugins(): array
{
    return [
        new SupplierPublisherTriggerPlugin(),
    ];
}
```

---

## Client Layer (Search/Query Pipeline)

### 1. SupplierSearchQueryPlugin

**File**: `Client/SupplierSearch/Plugin/Elasticsearch/Query/SupplierSearchQueryPlugin.php`

**Purpose**: Builds the Elasticsearch query to search for suppliers.

```php
class SupplierSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
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

        // Filter by document type
        $boolQuery->addMust(
            new MatchQuery(SupplierSearchConfig::KEY_TYPE, static::RESOURCE_TYPE)
        );

        $query->setQuery($boolQuery);

        return $query;
    }
}
```

**Key Concepts**:
- `SOURCE_IDENTIFIER`: Elasticsearch index name (`'supplier'`)
- `RESOURCE_TYPE`: Document type value (`'supplier'`)
- `SearchContextTransfer`: Tells SearchClient which index to query
- `BoolQuery`: Allows combining multiple filters/queries

**Best Practices**:
- Use constants for index name and type
- Use lazy-initialized properties (PHP 8.4 hooks)
- Filter by type to distinguish from other documents
- Keep base query simple - use expanders for dynamic filters

### 2. SupplierSearchResultFormatterPlugin

**File**: `Client/SupplierSearch/Plugin/Elasticsearch/ResultFormatter/SupplierSearchResultFormatterPlugin.php`

**Purpose**: Converts Elasticsearch hits to Transfer objects.

```php
class SupplierSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    protected const string NAME = 'SupplierSearchCollection';

    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): SupplierCollectionTransfer
    {
        $collection = new SupplierCollectionTransfer();

        foreach ($searchResult->getResults() as $document) {
            $source = $document->getSource();

            // Extract nested search-result-data
            $data = $source[SupplierSearchConfig::KEY_SEARCH_RESULT_DATA] ?? [];

            $transfer = (new SupplierTransfer())->fromArray($data, true);
            $collection->addSupplier($transfer);
        }

        return $collection;
    }
}
```

**Critical Points**:
- Read from `KEY_SEARCH_RESULT_DATA` (where Writer stores full data)
- Use `fromArray($data, true)` - `true` = ignore missing keys
- Return Collection transfer
- `NAME` constant used when multiple formatters return keyed array

**Best Practices**:
- Always use constants for field names
- Handle missing data gracefully (empty array fallback)
- Return Collection transfer, not plain array
- Extend `AbstractElasticsearchResultFormatterPlugin`

### 3. SupplierSearchReader

**File**: `Client/SupplierSearch/Reader/SupplierSearchReader.php`

**Purpose**: Orchestrates the search: expand query → execute → format results.

```php
class SupplierSearchReader implements SupplierSearchReaderInterface
{
    public function __construct(
        protected SearchClientInterface $searchClient,
        protected QueryInterface $supplierSearchQueryPlugin,
        protected array $queryExpanderPlugins,
        protected array $resultFormatterPlugins,
    ) {}

    public function searchSuppliers(array $requestParameters = []): SupplierCollectionTransfer
    {
        // 1. Expand query (add pagination, filters, etc.)
        $searchQuery = $this->searchClient->expandQuery(
            $this->supplierSearchQueryPlugin,
            $this->queryExpanderPlugins,
            $requestParameters,
        );

        // 2. Execute search
        $result = $this->searchClient->search(
            $searchQuery,
            $this->resultFormatterPlugins,
            $requestParameters,
        );

        // 3. Return formatted result
        if ($result instanceof SupplierCollectionTransfer) {
            return $result;
        }

        return $result['SupplierSearchCollection'] ?? new SupplierCollectionTransfer();
    }
}
```

**Flow**:
1. **Expand**: QueryExpanders modify the query (add filters, pagination, sorting)
2. **Execute**: SearchClient sends query to Elasticsearch
3. **Format**: ResultFormatters convert ES response to Transfers

**Best Practices**:
- Use SearchClient methods (don't query ES directly)
- Let expanders handle dynamic query modifications
- Handle both direct return and keyed array result
- Always return Collection transfer, never null

### 4. SupplierSearchClient

**File**: `Client/SupplierSearch/SupplierSearchClient.php`

**Purpose**: Public API for searching suppliers from Elasticsearch.

```php
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

**Best Practices**:
- Keep Client thin - delegate to Reader
- Accept request parameters (for filters, pagination)
- Return Collection transfer
- Document in ClientInterface

### 5. SupplierSearchDependencyProvider

**File**: `Client/SupplierSearch/SupplierSearchDependencyProvider.php`

**Purpose**: Provides external dependencies and plugin stacks.

```php
class SupplierSearchDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const PLUGIN_SUPPLIER_SEARCH_QUERY = 'PLUGIN_SUPPLIER_SEARCH_QUERY';
    public const PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER = 'PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER';
    public const PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER = 'PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, fn(Container $c) =>
            $c->getLocator()->search()->client()
        );

        $container->set(static::PLUGIN_SUPPLIER_SEARCH_QUERY, fn(): QueryInterface =>
            new SupplierSearchQueryPlugin()
        );

        $container->set(static::PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER, fn(): array => [
            new SupplierSearchResultFormatterPlugin(),
        ]);

        $container->set(static::PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER, fn(): array => [
            // Add expanders here (pagination, filtering, etc.)
        ]);

        return $container;
    }
}
```

**Best Practices**:
- Use constants for dependency keys
- Provide Search client via locator
- Create query plugin as singleton
- Provide plugin arrays for extensibility
- Don't create bridge classes

### 6. SupplierSearchFactory

**File**: `Client/SupplierSearch/SupplierSearchFactory.php`

**Purpose**: Creates Client layer objects and retrieves dependencies.

```php
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
        return $this->getProvidedDependency(
            SupplierSearchDependencyProvider::CLIENT_SEARCH
        );
    }

    // ... getters for plugins
}
```

**Best Practices**:
- One factory method per business object
- Get dependencies from DependencyProvider
- Keep methods simple (just instantiation)
- Use proper type hints

---

## Data Flow

### Publish Flow (Zed → Elasticsearch)

```
1. Supplier Entity Change (INSERT/UPDATE)
    ↓
2. Propel Event Dispatcher
    ↓
3. SupplierWritePublisherPlugin (listens to event)
    ↓
4. SupplierSearchWriter::writeCollectionBySupplierEvents()
    ↓
5. Structure data with KEY_SEARCH_RESULT_DATA
    ↓
6. Save to pyz_supplier_search table
    ↓
7. Synchronization behavior triggers
    ↓
8. Message added to sync.search.supplier queue
    ↓
9. QueueWorker processes message
    ↓
10. Sync to Elasticsearch supplier index
```

### Search Flow (Glue API → Elasticsearch)

```
1. GET /suppliers
    ↓
2. SuppliersStorefrontProvider
    ↓
3. SupplierClient::getSuppliers()
    ↓
4. SupplierSearchClient::searchSuppliers()
    ↓
5. SupplierSearchReader::searchSuppliers()
    ↓
6. SearchClient::expandQuery() [with QueryExpanders]
    ↓
7. SearchClient::search() [execute ES query]
    ↓
8. Elasticsearch returns hits
    ↓
9. SupplierSearchResultFormatterPlugin::formatSearchResult()
    ↓
10. Extract KEY_SEARCH_RESULT_DATA
    ↓
11. Convert to SupplierTransfer objects
    ↓
12. Return SupplierCollectionTransfer
```

---

## Commands

### Publish Suppliers to Search

```bash
# Trigger publish for all suppliers
docker/sdk cli console publish:trigger-events -r supplier

# Trigger for specific IDs
docker/sdk cli console publish:trigger-events -r supplier -i 1,2,3

# Process the publish queue
docker/sdk cli console queue:worker:start publish.search.supplier --stop-when-empty
```

### Sync to Elasticsearch

```bash
# Process sync queue
docker/sdk cli console queue:worker:start sync.search.supplier --stop-when-empty

# Or process all queues
docker/sdk cli console queue:worker:start --stop-when-empty
```

### Verify Data

```bash
# Check pyz_supplier_search table
docker/sdk cli console propel:sql:execute "SELECT id_supplier_search, fk_supplier, data FROM pyz_supplier_search LIMIT 5"

# Query Elasticsearch
curl -X GET "http://localhost:10005/de_supplier/_search?pretty"

# Count documents
curl -X GET "http://localhost:10005/de_supplier/_count?pretty"
```

---

## Common Issues & Solutions

### Issue: Empty search results

**Causes**:
1. Data not published: Run `publish:trigger-events -r supplier`
2. Data not synced: Run `queue:worker:start sync.search.supplier`
3. Wrong index name in QueryPlugin: Check `SOURCE_IDENTIFIER`

### Issue: ResultFormatter returns empty transfers

**Cause**: Data not stored under `KEY_SEARCH_RESULT_DATA`

**Solution**: Check SupplierSearchWriter structures data correctly:
```php
$searchData = [
    SupplierSearchConfig::KEY_SEARCH_RESULT_DATA => $supplierTransfer->toArray(),
    // ...
];
```

### Issue: publish:trigger-events fails with "resource not found"

**Cause**: SupplierPublisherTriggerPlugin not registered

**Solution**: Register in `PublisherDependencyProvider::getPublisherTriggerPlugins()`

### Issue: "Call to undefined method: modifiedToArray"

**Cause**: `getData()` returns Propel entities instead of Transfers

**Solution**: Convert entities to Transfers in PublisherTriggerPlugin:
```php
foreach ($entities as $entity) {
    $transfers[] = (new SupplierTransfer())->fromArray($entity->toArray(), true);
}
return $transfers;
```

---

## Best Practices Summary

### DO ✅

1. **Use Constants Everywhere**
   - Field names from Config
   - Queue names from Config
   - Event names from Config

2. **Structure ES Documents Properly**
   - Use `search-result-data` key
   - Include searchable fields
   - Add type field for filtering

3. **Handle Bulk Events**
   - Process multiple IDs at once
   - Use indexed arrays
   - Load existing search records

4. **Test Publishing**
   - Use `publish:trigger-events` regularly
   - Check pyz_supplier_search table
   - Verify ES documents

5. **Use Query Expanders**
   - For pagination
   - For dynamic filters
   - For sorting

### DON'T ❌

1. **Don't Use Magic Strings**
   - Always use Config constants
   - Never hardcode field names
   - Never hardcode index names

2. **Don't Return Propel Entities**
   - Always convert to Transfers
   - Especially in PublisherTriggerPlugin
   - Check return types

3. **Don't Skip search-result-data**
   - ResultFormatter needs this
   - Without it, data is flat
   - Transfers will be empty

4. **Don't Query ES Directly**
   - Use SearchClient
   - Use QueryPlugins
   - Use ResultFormatters

5. **Don't Forget Registration**
   - Register Write Plugin
   - Register Trigger Plugin
   - Register in DependencyProvider

---

## Related Modules

- **Supplier**: Source of data
- **SuppliersApi**: Glue API consumer
- **Publisher**: Event system
- **Queue**: Message queue system
- **Search**: Elasticsearch integration

---

## Further Reading

- [Spryker Publish & Sync](https://docs.spryker.com/docs/scos/dev/back-end-development/data-manipulation/data-publishing/publish-and-synchronization.html)
- [Elasticsearch Integration](https://docs.spryker.com/docs/pbc/all/search/install-and-upgrade/install-search.html)
- [Search Plugins](https://docs.spryker.com/docs/pbc/all/search/base-shop/extend-and-customize/configure-search-features.html)
