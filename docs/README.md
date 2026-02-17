# SprykerAcademy Project Documentation

## Overview

This documentation covers all custom modules developed in the **SprykerAcademy** namespace for the Intermediate Backend Development course. The project demonstrates end-to-end Spryker development including business logic, data persistence, search integration, and REST API exposure.

## Project Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Frontend / API Clients                │
└─────────────────────┬───────────────────────────────────┘
                      │ HTTP/REST
┌─────────────────────▼───────────────────────────────────┐
│             Glue Storefront (API Layer)                  │
│  • SuppliersApi - REST endpoints                        │
│  • SupplierLocationsApi - Related resources             │
└─────────────────────┬───────────────────────────────────┘
                      │ Locator
┌─────────────────────▼───────────────────────────────────┐
│                 Client Layer (Facade)                    │
│  • SupplierClient - Data access facade                  │
│  • SupplierSearchClient - Elasticsearch queries         │
└─────────┬───────────────────┬─────────────────────────────┘
          │                   │
          │ ZedRequest RPC    │ Elasticsearch
          │                   │
┌─────────▼─────────┐  ┌──────▼──────────────────────────┐
│   Zed Backend     │  │   Elasticsearch Cluster         │
│  • Business       │  │   • supplier index              │
│  • Persistence    │  │   • Denormalized search data    │
│  • Communication  │  │                                  │
└─────────┬─────────┘  └─────────────────────────────────┘
          │
          │ Propel ORM
          │
┌─────────▼─────────────────────────────────────────────┐
│              PostgreSQL Database                       │
│  • pyz_supplier                                       │
│  • pyz_supplier_search (sync table)                   │
│  • pyz_supplier_location                              │
└───────────────────────────────────────────────────────┘
```

## Modules Documentation

### Core Modules

#### 1. [Supplier Module](modules/Supplier.md)
**Purpose**: Core business logic for supplier management

**Layers**:
- **Zed Business**: CRUD operations, business logic
- **Zed Persistence**: Database access (Repository, EntityManager)
- **Zed Communication**: Gateway Controller for RPC
- **Client**: Facade for Glue API access

**Key Concepts**:
- Transfer objects
- Facade pattern
- Repository/EntityManager separation
- ZedRequest RPC communication

**Files**: ~20 PHP classes + transfers + schema

---

#### 2. [SupplierSearch Module](modules/SupplierSearch.md)
**Purpose**: Elasticsearch integration via Publish & Sync

**Layers**:
- **Zed Business**: SupplierSearchWriter (data mapper)
- **Zed Persistence**: pyz_supplier_search sync table
- **Zed Communication**: Publisher plugins (Write, Trigger)
- **Client**: QueryPlugin, ResultFormatter, Reader

**Key Concepts**:
- Publish & Sync pattern
- Elasticsearch denormalization
- Event-driven updates
- Search-result-data structure
- Query expansion

**Files**: ~15 PHP classes + schema + config

---

#### 3. [SuppliersApi Module](modules/SuppliersApi.md)
**Purpose**: REST API endpoints for Glue Storefront

**Components**:
- **Provider**: Request handling
- **Mapper**: Transfer → Resource conversion
- **Resource YAML**: API definition
- **Config**: ApplicationServices registration

**Key Concepts**:
- API Platform integration
- RESTful design
- Resource transformation
- Symfony DI

**Files**: ~5 PHP classes + YAML + config

---

### Supporting Modules

#### 4. SupplierLocation Module
**Purpose**: Related entity for supplier addresses

**Similar to Supplier module with**:
- One-to-one relationship with Supplier
- Own search integration
- Own API endpoints

---

#### 5. SupplierGui Module
**Purpose**: Backoffice management interface

**Components**:
- Controllers (Index, Create, Edit, Delete)
- Forms (Create, Edit)
- Tables (List view)
- Templates (Twig)

**Access**: `/supplier-gui` in Backoffice

---

#### 6. SupplierDataImport Module
**Purpose**: CSV import for bulk supplier creation

**Components**:
- DataImport plugins
- Data set interfaces
- Writer steps
- Validation steps

**Usage**:
```bash
docker/sdk cli console data:import:supplier
```

---

## Data Flow Examples

### 1. Create Supplier (Backoffice → Database)

```
User fills form in SupplierGui
    ↓
POST /supplier-gui/create
    ↓
CreateController::indexAction()
    ↓
SupplierFacade::createSupplier()
    ↓
SupplierWriter::createSupplier()
    ↓
SupplierEntityManager::createSupplier()
    ↓
Propel INSERT INTO pyz_supplier
    ↓
Event: Entity.pyz_supplier.create
    ↓
Returns SupplierTransfer with ID
```

### 2. Publish to Search (Database → Elasticsearch)

```
Event: Entity.pyz_supplier.create/update
    ↓
SupplierWritePublisherPlugin::handleBulk()
    ↓
SupplierSearchWriter::writeCollectionBySupplierEvents()
    ↓
Structure data with search-result-data
    ↓
INSERT/UPDATE pyz_supplier_search
    ↓
Synchronization behavior triggers
    ↓
Message → sync.search.supplier queue
    ↓
QueueWorker processes
    ↓
Sync to Elasticsearch supplier index
```

### 3. API Request (Frontend → Elasticsearch → JSON)

```
GET /suppliers?page=1&ipp=10
    ↓
API Platform routing
    ↓
SuppliersStorefrontProvider::provide()
    ↓
SupplierClient::getSuppliers()
    ↓
SupplierSearchClient::searchSuppliers()
    ↓
SupplierSearchQueryPlugin (builds ES query)
    ↓
Elasticsearch query execution
    ↓
SupplierSearchResultFormatterPlugin (ES → Transfers)
    ↓
SupplierMapper (Transfer → Resource)
    ↓
API Platform serialization
    ↓
JSON Response
```

---

## Key Concepts

### Transfer Objects

**What**: Type-safe DTOs for data exchange

**Why**:
- Type safety
- IDE autocomplete
- Immutable contracts
- Layer independence

**Usage**:
```php
$transfer = new SupplierTransfer();
$transfer->setName('ACME');
$transfer->setStatus(1);

// Convert from array
$transfer->fromArray($data, true);

// Convert to array (camelCase)
$array = $transfer->toArray(false, true);
```

**Best Practice**: ALWAYS use Transfers between layers, never Propel entities.

---

### Facade Pattern

**What**: Single entry point to module's business logic

**Why**:
- Encapsulation
- Clear public API
- Hides implementation
- Easy testing

**Usage**:
```php
// Other modules only call Facade
$suppliers = $this->supplierFacade->getSuppliers($criteria);

// Facade delegates to business models
public function getSuppliers($criteria): array
{
    return $this->getFactory()
        ->createSupplierReader()
        ->getSuppliers($criteria);
}
```

**Best Practice**: Keep Facade thin, delegate to Business models.

---

### Repository / EntityManager

**What**: Separation of read and write operations

**Why**:
- Read-only guarantee for Repository
- Transaction management in EntityManager
- Clear responsibility
- Easier caching

**Repository** (Read-only):
```php
public function getSuppliers(SupplierCriteriaTransfer $criteria): array
{
    $query = $this->getFactory()->createSupplierQuery();

    // Apply filters
    if ($criteria->getIdsSupplier()) {
        $query->filterByIdSupplier_In($criteria->getIdsSupplier());
    }

    return $this->buildQueryResults($query);
}
```

**EntityManager** (Write-only):
```php
public function createSupplier(SupplierTransfer $transfer): SupplierTransfer
{
    $entity = new PyzSupplier();
    $entity = $this->getFactory()
        ->createSupplierMapper()
        ->mapTransferToEntity($transfer, $entity);

    $entity->save();

    return $this->getFactory()
        ->createSupplierMapper()
        ->mapEntityToTransfer($entity, $transfer);
}
```

---

### Publish & Sync

**What**: Event-driven denormalization to storage systems

**Why**:
- Fast reads (pre-computed data)
- Separate read/write models
- Eventual consistency
- Scalability

**Publish** (Write to sync table):
1. Entity change triggers event
2. Publisher plugin listens
3. Writer structures data
4. Save to sync table (e.g., pyz_supplier_search)

**Sync** (Sync to storage):
1. Synchronization behavior detects change
2. Message added to queue
3. Queue worker processes
4. Data synced to Elasticsearch/Redis/etc.

**Commands**:
```bash
# Manual publish
docker/sdk cli console publish:trigger-events -r supplier

# Process sync queue
docker/sdk cli console queue:worker:start --stop-when-empty
```

---

### ZedRequest RPC

**What**: Client → Zed communication via HTTP

**Why**:
- Glue has no database access
- Needs fresh data
- Synchronous when needed

**Flow**:
```
Client/Supplier/Zed/SupplierStub
    ↓ HTTP POST
Zed/Supplier/Communication/Controller/GatewayController
    ↓
Zed/Supplier/Business/SupplierFacade
    ↓
Returns SupplierTransfer
```

**URL Convention**: `/{module}/gateway/{action-name}`

**Best Practice**: Use for single-record lookups and writes. Use Elasticsearch for collections/search.

---

## Commands Reference

### Development

```bash
# Generate transfers after XML changes
docker/sdk cli console transfer:generate

# Generate API resources after YAML changes
docker/sdk cli console glue api:generate

# Generate Propel models after schema changes
docker/sdk cli console propel:model:build

# Run database migrations
docker/sdk cli console propel:migrate
```

### Data Management

```bash
# Import suppliers from CSV
docker/sdk cli console data:import:supplier

# Publish suppliers to search
docker/sdk cli console publish:trigger-events -r supplier

# Process all queues
docker/sdk cli console queue:worker:start --stop-when-empty
```

### Cache Management

```bash
# Clear all caches
docker/sdk cli console cache:empty-all

# Clear router cache
docker/sdk cli console router:cache:clear

# Clear Glue API cache
docker/sdk cli console cache:clear-storefront-resources
```

### Debugging

```bash
# Check routes
docker/sdk cli console debug:router | grep supplier

# Validate API Platform config
docker/sdk cli console api:platform:validate

# Query Elasticsearch
curl "http://localhost:10005/de_supplier/_search?pretty"

# Check sync table
docker/sdk cli console propel:sql:execute "SELECT * FROM pyz_supplier_search LIMIT 5"
```

---

## Testing

### Unit Tests

Test business logic in isolation:

```php
class SupplierFacadeTest extends Unit
{
    public function testCreateSupplier(): void
    {
        $transfer = (new SupplierTransfer())
            ->setName('Test Supplier')
            ->setStatus(1);

        $result = $this->tester->getFacade()->createSupplier($transfer);

        $this->assertNotNull($result->getIdSupplier());
        $this->assertEquals('Test Supplier', $result->getName());
    }
}
```

### Integration Tests

Test full stack:

```php
class SuppliersRestApiTest extends Unit
{
    public function testGetSuppliers(): void
    {
        $this->tester->haveSupplier(['name' => 'Test']);

        $this->tester->sendGet('/suppliers');

        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();
    }
}
```

### Run Tests

```bash
# All tests
docker/sdk cli vendor/bin/codecept run

# Specific suite
docker/sdk cli vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/Supplier

# With coverage
docker/sdk cli vendor/bin/codecept run --coverage
```

---

## Best Practices Summary

### Architecture

✅ **DO**:
- Use Transfer objects everywhere
- Follow layer responsibilities
- Keep Facade thin
- Separate reads (Repository) from writes (EntityManager)
- Use constants instead of magic strings

❌ **DON'T**:
- Pass Propel entities between layers
- Put business logic in Repository
- Access database from Business layer
- Use magic strings
- Skip validation

### Elasticsearch

✅ **DO**:
- Structure data with `search-result-data` key
- Use constants for field names
- Handle bulk events efficiently
- Test publishing regularly
- Use Query Expanders for dynamic filters

❌ **DON'T**:
- Return Propel entities from PublisherTriggerPlugin
- Query Elasticsearch directly (use SearchClient)
- Forget to run `publish:trigger-events` after changes
- Hardcode index names
- Skip `search-result-data` structure

### Glue API

✅ **DO**:
- Validate input
- Return null for 404
- Use `toArray(false, true)` for camelCase
- Set `identifier: true` in YAML
- Register Clients in ApplicationServices.php

❌ **DON'T**:
- Use snake_case in mapper
- Return null from collection
- Access database directly
- Skip validation
- Forget to run `glue api:generate`

---

## Common Issues

### Issue: Transfer not found

**Solution**:
```bash
docker/sdk cli console transfer:generate
```

### Issue: API 404

**Solution**:
```bash
docker/sdk cli console glue api:generate
docker/sdk cli console cache:empty-all
```

### Issue: Empty Elasticsearch results

**Solution**:
```bash
docker/sdk cli console publish:trigger-events -r supplier
docker/sdk cli console queue:worker:start --stop-when-empty
```

### Issue: Properties null in API

**Solution**: Use `toArray(false, true)` in mapper for camelCase

---

## File Structure

```
src/SprykerAcademy/
├── Client/
│   ├── Supplier/                 # Data access facade
│   └── SupplierSearch/           # Elasticsearch queries
├── Glue/
│   └── SuppliersApi/             # REST API
├── Shared/
│   ├── Supplier/                 # Transfer definitions
│   └── SupplierSearch/           # Config constants
└── Zed/
    ├── Supplier/                 # Core business logic
    ├── SupplierSearch/           # Publish & Sync
    ├── SupplierGui/              # Backoffice UI
    └── SupplierDataImport/       # CSV import

config/
├── GlueStorefront/
│   ├── ApplicationServices.php   # DI registration
│   └── packages/
│       └── spryker_api_platform.php

docs/
├── README.md                     # This file
└── modules/
    ├── Supplier.md
    ├── SupplierSearch.md
    └── SuppliersApi.md
```

---

## Further Learning

### Spryker Documentation
- [Architecture Concepts](https://docs.spryker.com/docs/scos/dev/architecture/architecture.html)
- [Transfer Objects](https://docs.spryker.com/docs/scos/dev/back-end-development/data-manipulation/data-ingestion/structural-preparations/create-use-and-extend-the-transfer-objects.html)
- [Publish & Sync](https://docs.spryker.com/docs/scos/dev/back-end-development/data-manipulation/data-publishing/publish-and-synchronization.html)
- [Glue API](https://docs.spryker.com/docs/scos/dev/glue-api-guides/glue-rest-api.html)

### API Platform
- [API Platform Docs](https://api-platform.com/docs/)
- [Symfony DI](https://symfony.com/doc/current/service_container.html)

### Elasticsearch
- [Elasticsearch Guide](https://www.elastic.co/guide/en/elasticsearch/reference/current/index.html)
- [Spryker Search](https://docs.spryker.com/docs/pbc/all/search/base-shop/search.html)

---

## Support

For questions or issues:
1. Check the module-specific documentation
2. Review common issues section
3. Check Spryker documentation
4. Review code examples in modules

---

## Version History

- **v1.0.0** - Initial documentation (2026-02-17)
  - Supplier module
  - SupplierSearch module
  - SuppliersApi module
  - Architecture overview
  - Best practices guide
