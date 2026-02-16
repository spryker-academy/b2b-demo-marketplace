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

### 2. Zed Business Layer

Standard Spryker Zed architecture. Required for ZedRequest RPC and for the publish & sync pipeline that feeds Elasticsearch.

Key files:
- `Zed/{Module}/Persistence/{Module}Repository.php` — reads from DB via Propel
- `Zed/{Module}/Persistence/{Module}PersistenceFactory.php` — creates Propel queries and mappers
- `Zed/{Module}/Business/Reader/{Module}Reader.php` — business logic for reads
- `Zed/{Module}/Business/{Module}BusinessFactory.php` — creates readers/writers
- `Zed/{Module}/Business/{Module}Facade.php` — public API

### 3. Zed Gateway Controller

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

### 4. SupplierSearch Client Module (Elasticsearch reads)

This module reads supplier data from Elasticsearch. It follows the standard Spryker Search Client pattern: QueryPlugin + ResultFormatterPlugin + Reader.

#### 4a. Query Plugin

The query plugin builds the Elastica query. It filters by the `type` field matching the resource name and returns only the `search-result-data` object from each document.

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

class SupplierSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    protected const SOURCE_IDENTIFIER = 'supplier';
    protected const RESOURCE_TYPE = 'supplier';

    protected Query $query;
    protected ?SearchContextTransfer $searchContextTransfer = null;

    public function __construct()
    {
        $this->query = $this->createSearchQuery();
    }

    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    public function getSearchContext(): SearchContextTransfer
    {
        if ($this->searchContextTransfer === null) {
            $this->searchContextTransfer = (new SearchContextTransfer())
                ->setSourceIdentifier(static::SOURCE_IDENTIFIER);
        }

        return $this->searchContextTransfer;
    }

    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }

    protected function createSearchQuery(): Query
    {
        $query = new Query();
        $boolQuery = new BoolQuery();

        $typeFilter = (new MatchQuery())->setField('type', static::RESOURCE_TYPE);
        $boolQuery->addMust($typeFilter);

        $query->setQuery($boolQuery);
        $query->setSource(['search-result-data']);

        return $query;
    }
}
```

**Key points:**
- `SOURCE_IDENTIFIER` must match the `resource` value from the synchronization behavior in `pyz_supplier_search.schema.xml`
- `RESOURCE_TYPE` must match the `type` value in the sync params (e.g., `{"type":"page"}` means type = the resource name used by the sync behavior)
- `setSource(['search-result-data'])` — only return the data payload, not the full ES document

#### 4b. Result Formatter Plugin

Maps Elasticsearch hits to Transfer objects.

```php
// src/SprykerAcademy/Client/SupplierSearch/Plugin/Elasticsearch/ResultFormatter/SupplierSearchResultFormatterPlugin.php
namespace SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

class SupplierSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    protected const NAME = 'SupplierSearchCollection';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): SupplierCollectionTransfer
    {
        $supplierCollectionTransfer = new SupplierCollectionTransfer();

        foreach ($searchResult->getResults() as $document) {
            $source = $document->getSource();
            $data = $source['search-result-data'] ?? [];

            $supplierTransfer = (new SupplierTransfer())->fromArray($data, true);
            $supplierCollectionTransfer->addSupplier($supplierTransfer);
        }

        return $supplierCollectionTransfer;
    }
}
```

**Key points:**
- Extends `AbstractElasticsearchResultFormatterPlugin` from `spryker/search-elasticsearch`
- `fromArray($data, true)` — the `true` flag enables snake_case to camelCase mapping (ES stores data in snake_case)
- The `NAME` constant is used as the key when multiple formatters return results in an array

#### 4c. Reader

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

#### 4d. DependencyProvider, Factory, Client

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

### 5. Supplier Client Module (facade for Glue)

The `SupplierClient` is the single entry point used by the Glue provider. It delegates to `SupplierSearchClient` for collections (Elasticsearch) and to the `SupplierStub` for single-record lookups (ZedRequest RPC).

#### 5a. Zed Stub (for RPC calls)

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

#### 5b. DependencyProvider, Factory, Client

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

### 6. API Platform Resource Definition (YAML)

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

### 7. Glue Storefront Provider

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

### 8. Glue Mapper

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

### 9. Register Services in ApplicationServices.php

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

### 10. API Platform Config

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

### 11. Build and Test

```bash
# Generate transfers (SupplierCollectionTransfer, etc.)
docker/sdk cli console transfer:generate

# Generate API Platform resource classes from YAML
docker/sdk cli console glue api:generate

# Ensure data is synced to Elasticsearch
docker/sdk cli console queue:worker:start --stop-when-empty

# Clear all caches (routing, DI container, etc.)
docker/sdk cli console cache:empty-all

# Test collection (reads from Elasticsearch)
curl http://glue-storefront.eu.spryker.local/suppliers

# Test single record (reads via ZedRequest RPC)
curl http://glue-storefront.eu.spryker.local/suppliers/1
```

## Elasticsearch Publish & Sync Prerequisites

For the SupplierSearchClient to return data, the publish & sync pipeline must be set up:

1. **Search table** — `pyz_supplier_search` with the `synchronization` behavior in `pyz_supplier_search.schema.xml`:
   ```xml
   <behavior name="synchronization">
       <parameter name="resource" value="supplier"/>
       <parameter name="key_suffix_column" value="fk_supplier"/>
       <parameter name="queue_group" value="sync.search.supplier"/>
       <parameter name="params" value='{"type":"page"}'/>
   </behavior>
   ```

2. **ES schema** — `src/SprykerAcademy/Shared/SupplierSearch/Schema/supplier.json` defines the index mappings.

3. **Publisher plugin** — `SupplierWritePublisherPlugin` listens to supplier entity events and writes to `pyz_supplier_search`.

4. **Queue worker** — Processes the `sync.search.supplier` queue to push data from `pyz_supplier_search.data` column into the Elasticsearch index.

The data flow: `Supplier table change -> Event -> Publisher -> pyz_supplier_search -> Queue -> Elasticsearch`

## Common Pitfalls

| Error | Cause | Fix |
|---|---|---|
| `Database map was not initialized` | Using Propel/Facade directly in Glue | Use a Client module with ZedRequest RPC or Elasticsearch |
| `Unable to generate an IRI` | Resource identifier property is null | Ensure `toArray(false, true)` for camelCased keys; ensure a property has `identifier: true` in YAML |
| `Could not find "X" in any of the attached containers` | Class not registered in Symfony DI or not auto-resolvable | Register in `ApplicationServices.php` or instantiate directly |
| `Class not found` for generated resource | API resource class not generated after YAML change | Run `docker/sdk cli console glue api:generate` |
| 404 Not Found | Route cache is stale | Run `docker/sdk cli console cache:empty-all` |
| Empty response data from ZedRequest | Returning arrays instead of Collection transfer | Wrap arrays in a Collection transfer (`SupplierCollectionTransfer`) |
| Empty collection from Elasticsearch | Data not synced or wrong `SOURCE_IDENTIFIER` | Run `queue:worker:start --stop-when-empty`; verify `SOURCE_IDENTIFIER` matches the sync behavior `resource` value |
| ES returns hits but transfers are empty | Wrong `fromArray` mapping | Use `fromArray($data, true)` — the `true` enables snake_case to camelCase conversion |

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
    Business/Writer/SupplierSearchWriter.php      # Publish & sync writer
    Persistence/
      SupplierSearchEntityManager.php
      SupplierSearchRepository.php
      Propel/Schema/pyz_supplier_search.schema.xml
    Communication/Plugin/Publisher/
      SupplierWritePublisherPlugin.php

  Client/SupplierSearch/                          # Reads from Elasticsearch
    Plugin/Elasticsearch/
      Query/SupplierSearchQueryPlugin.php         # Elastica query builder
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
```
