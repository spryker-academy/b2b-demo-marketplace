# Supplier Module

## Overview

The **Supplier** module is the core business logic module that manages supplier data in the system. It follows Spryker's standard architecture with clear separation between Business, Persistence, and Communication layers.

## Purpose

- Manage supplier entities (CRUD operations)
- Provide business logic for supplier-related operations
- Serve as the data source for other modules (GUI, Search, API)
- Handle supplier data persistence and retrieval

## Architecture Layers

### 1. Shared Layer (`Shared/Supplier`)

**Location**: `src/SprykerAcademy/Shared/Supplier/`

#### Transfer Definitions

**File**: `Transfer/supplier.transfer.xml`

```xml
<transfer name="Supplier">
    <property name="idSupplier" type="int" />
    <property name="name" type="string" />
    <property name="description" type="string" />
    <property name="status" type="int" />
    <property name="email" type="string" />
    <property name="phone" type="string" />
</transfer>

<transfer name="SupplierCriteria">
    <property name="idSupplier" type="int" />
    <property name="idsSupplier" type="int[]" singular="idSupplier" />
    <property name="name" type="string" />
</transfer>

<transfer name="SupplierCollection">
    <property name="suppliers" type="Supplier[]" singular="supplier" />
</transfer>
```

**Purpose**: Transfer objects define the data contracts between layers. They are type-safe, immutable DTOs (Data Transfer Objects).

**Best Practices**:
- Always use Transfer objects for data exchange between layers
- Never pass Propel entities outside the Persistence layer
- Use `Criteria` transfers for filtering/querying
- Use `Collection` transfers for returning multiple items

---

### 2. Zed Layer (Backend)

**Location**: `src/SprykerAcademy/Zed/Supplier/`

#### Business Layer

##### 2.1 Facade

**File**: `Business/SupplierFacade.php`

```php
class SupplierFacade extends AbstractFacade implements SupplierFacadeInterface
{
    public function getSuppliers(SupplierCriteriaTransfer $criteriaTransfer): array;
    public function findSupplierById(int $idSupplier): ?SupplierTransfer;
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;
    public function updateSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;
}
```

**Purpose**: Public API of the module. Other modules interact with Supplier only through the Facade.

**Best Practices**:
- Keep facade methods simple and delegate to Business models
- Always return Transfer objects, never entities
- Document expected behavior in FacadeInterface
- Use descriptive method names (get, find, create, update, delete)

##### 2.2 Business Factory

**File**: `Business/SupplierBusinessFactory.php`

**Purpose**: Creates Business layer objects (Readers, Writers).

**Best Practices**:
- Create one factory method per business model
- Inject dependencies from DependencyProvider
- Keep factory methods simple (just instantiation)

##### 2.3 Reader

**File**: `Business/Reader/SupplierReader.php`

**Purpose**: Read operations and business logic for retrieving suppliers.

**Pattern**:
```php
class SupplierReader
{
    public function __construct(
        protected SupplierRepositoryInterface $supplierRepository,
    ) {}

    public function getSuppliers(SupplierCriteriaTransfer $criteriaTransfer): array
    {
        return $this->supplierRepository->getSuppliers($criteriaTransfer);
    }
}
```

**Best Practices**:
- Keep business logic here, not in Repository
- Validate criteria before calling Repository
- Transform data if needed (e.g., filtering, sorting)
- Return Transfer objects

##### 2.4 Writer

**File**: `Business/Writer/SupplierWriter.php`

**Purpose**: Write operations (create, update, delete).

**Pattern**:
```php
class SupplierWriter
{
    public function __construct(
        protected SupplierEntityManagerInterface $entityManager,
    ) {}

    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        // Validation
        $this->validateSupplier($supplierTransfer);

        // Persist
        return $this->entityManager->createSupplier($supplierTransfer);
    }
}
```

**Best Practices**:
- Add validation before persistence
- Use transactions for complex operations
- Trigger events after successful operations
- Return the persisted Transfer with ID populated

#### Persistence Layer

##### 2.5 Repository

**File**: `Persistence/SupplierRepository.php`

**Purpose**: Read-only database operations.

**Best Practices**:
- Only SELECT queries
- Use Propel Query objects
- Convert entities to Transfers before returning
- Use mappers for entity-to-transfer conversion

##### 2.6 EntityManager

**File**: `Persistence/SupplierEntityManager.php`

**Purpose**: Write database operations.

**Best Practices**:
- Only INSERT, UPDATE, DELETE queries
- Always return updated Transfer objects
- Use mappers for transfer-to-entity conversion
- Handle transactions properly

##### 2.7 Propel Mapper

**File**: `Persistence/Propel/Mapper/SupplierMapper.php`

**Purpose**: Convert between Propel entities and Transfer objects.

**Pattern**:
```php
class SupplierMapper
{
    public function mapEntityToTransfer(
        PyzSupplier $entity,
        SupplierTransfer $transfer
    ): SupplierTransfer {
        return $transfer->fromArray($entity->toArray(), true);
    }

    public function mapTransferToEntity(
        SupplierTransfer $transfer,
        PyzSupplier $entity
    ): PyzSupplier {
        $entity->fromArray($transfer->modifiedToArray());
        return $entity;
    }
}
```

**Best Practices**:
- Always use mappers for conversions
- Use `fromArray($data, true)` to ignore missing fields
- Handle null values appropriately
- Keep conversion logic centralized

#### Communication Layer

##### 2.8 Gateway Controller

**File**: `Communication/Controller/GatewayController.php`

**Purpose**: RPC endpoint for Client layer to call Zed.

**Pattern**:
```php
class GatewayController extends AbstractGatewayController
{
    public function findSupplierByIdAction(
        SupplierCriteriaTransfer $criteriaTransfer
    ): SupplierTransfer {
        $supplier = $this->getFacade()->findSupplierById(
            $criteriaTransfer->getIdSupplierOrFail()
        );

        // Gateway must ALWAYS return a Transfer, never null
        return $supplier ?? new SupplierTransfer();
    }
}
```

**Best Practices**:
- ALWAYS return Transfer objects, never null
- Keep Gateway thin - delegate to Facade
- Action methods must end with `Action` suffix
- URL: `/supplier/gateway/find-supplier-by-id` maps to `findSupplierByIdAction`

---

### 3. Client Layer

**Location**: `src/SprykerAcademy/Client/Supplier/`

#### Purpose

The Client layer acts as a facade for Glue (API layer) to access supplier data. It abstracts:
- **Elasticsearch queries** (via SupplierSearchClient) for collections
- **ZedRequest RPC calls** (via SupplierStub) for single records

#### 3.1 Client

**File**: `SupplierClient.php`

```php
class SupplierClient extends AbstractClient implements SupplierClientInterface
{
    // Elasticsearch (fast, for collections)
    public function getSuppliers(array $requestParameters = []): SupplierCollectionTransfer
    {
        return $this->getFactory()
            ->getSupplierSearchClient()
            ->searchSuppliers($requestParameters);
    }

    // ZedRequest RPC (for single items, always fresh data)
    public function findSupplierById(int $idSupplier): SupplierTransfer
    {
        $criteria = (new SupplierCriteriaTransfer())
            ->setIdSupplier($idSupplier);

        return $this->getFactory()
            ->createSupplierStub()
            ->findSupplierById($criteria);
    }
}
```

**Best Practices**:
- Use Elasticsearch for collections/search
- Use ZedRequest for single records or writes
- Keep Client thin - delegate to sub-clients or stubs
- Don't implement business logic here

#### 3.2 Zed Stub

**File**: `Zed/SupplierStub.php`

**Purpose**: Makes RPC calls to Zed Gateway Controller.

```php
class SupplierStub implements SupplierStubInterface
{
    public function findSupplierById(
        SupplierCriteriaTransfer $criteriaTransfer
    ): SupplierTransfer {
        return $this->zedRequestClient->call(
            '/supplier/gateway/find-supplier-by-id',
            $criteriaTransfer
        );
    }
}
```

**Best Practices**:
- One method per Gateway action
- URL format: `/{module}/gateway/{action-name}`
- Pass Transfer objects as parameters
- Cast return values to expected Transfer type

#### 3.3 Dependency Provider

**File**: `SupplierDependencyProvider.php`

**Purpose**: Provides external dependencies (other Clients).

**Best Practices**:
- Use constants for dependency keys
- Provide clients via locator: `$c->getLocator()->moduleName()->client()`
- Don't create bridge classes (Spryker internal pattern only)

#### 3.4 Factory

**File**: `SupplierFactory.php`

**Purpose**: Creates Client layer objects and retrieves dependencies.

**Best Practices**:
- Create stubs in factory
- Get external clients from provided dependencies
- Keep factory methods simple

---

## Database Schema

**Table**: `pyz_supplier`

```sql
CREATE TABLE pyz_supplier (
    id_supplier INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    status INTEGER DEFAULT 1,
    email VARCHAR(255),
    phone VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## Data Flow Examples

### 1. Create Supplier (Zed GUI)

```
SupplierGui (Form Submit)
    → SupplierFacade::createSupplier()
    → SupplierWriter::createSupplier()
    → SupplierEntityManager::createSupplier()
    → Propel: INSERT INTO pyz_supplier
    → Event: Entity.pyz_supplier.create
    → Returns SupplierTransfer with ID
```

### 2. Get Supplier by ID (API)

```
Glue Provider
    → SupplierClient::findSupplierById()
    → SupplierStub::findSupplierById() [ZedRequest RPC]
    → HTTP: POST /supplier/gateway/find-supplier-by-id
    → GatewayController::findSupplierByIdAction()
    → SupplierFacade::findSupplierById()
    → SupplierReader::findSupplierById()
    → SupplierRepository::findSupplierById()
    → Propel: SELECT * FROM pyz_supplier WHERE id = ?
    → Returns SupplierTransfer
```

### 3. Get Suppliers Collection (API)

```
Glue Provider
    → SupplierClient::getSuppliers()
    → SupplierSearchClient::searchSuppliers()
    → Elasticsearch: Query supplier index
    → Returns SupplierCollectionTransfer
```

---

## Best Practices Summary

### DO ✅

1. **Use Transfer Objects everywhere**
   - Between all layers
   - For all data exchange
   - With proper type hints

2. **Follow Layer Responsibilities**
   - Business: Logic
   - Persistence: Data access
   - Communication: External interfaces

3. **Use Mappers**
   - Convert entities to transfers
   - Centralize conversion logic
   - Handle null values

4. **Return Empty Transfers**
   - Never return null from Gateway
   - Return empty transfer if not found
   - Check for null in Client/Glue layers

5. **Use Constants**
   - For field names
   - For configuration values
   - From Shared Config classes

### DON'T ❌

1. **Don't Pass Entities Outside Persistence**
   - Never return Propel entities from Repository
   - Never accept entities in Business layer
   - Always convert to Transfers

2. **Don't Put Logic in Repository**
   - Only data access queries
   - No business rules
   - No validation

3. **Don't Call Propel from Business**
   - Use Repository/EntityManager
   - Never create QueryObjects in Business
   - No direct database access

4. **Don't Use Magic Strings**
   - Use constants
   - Define in Shared Config
   - Reference via Config classes

5. **Don't Skip Validation**
   - Validate in Writers
   - Check required fields
   - Validate business rules

---

## Common Patterns

### Reading Data

```php
// Facade → Reader → Repository → Propel Query
$suppliers = $this->getFacade()->getSuppliers($criteria);
```

### Writing Data

```php
// Facade → Writer → EntityManager → Propel Entity
$supplier = $this->getFacade()->createSupplier($transfer);
```

### RPC Communication

```php
// Client → Stub → ZedRequest → Gateway → Facade
$supplier = $this->getClient()->findSupplierById($id);
```

---

## Testing

### Unit Tests

Test business logic in isolation:

```php
class SupplierWriterTest extends Unit
{
    public function testCreateSupplierSetsDefaultStatus(): void
    {
        $transfer = new SupplierTransfer();
        $transfer->setName('Test Supplier');

        $result = $this->tester->getFacade()->createSupplier($transfer);

        $this->assertEquals(1, $result->getStatus());
    }
}
```

### Integration Tests

Test full stack:

```php
class SupplierFacadeTest extends Unit
{
    public function testFindSupplierByIdReturnsSupplier(): void
    {
        $supplier = $this->tester->haveSupplier(['name' => 'Test']);

        $result = $this->tester->getFacade()->findSupplierById(
            $supplier->getIdSupplier()
        );

        $this->assertEquals('Test', $result->getName());
    }
}
```

---

## Related Modules

- **SupplierSearch**: Elasticsearch integration
- **SupplierGui**: Backoffice UI
- **SupplierDataImport**: CSV import
- **SuppliersApi**: Glue API resources
- **SupplierLocation**: Related entity module

---

## Further Reading

- [Spryker Module Structure](https://docs.spryker.com)
- [Transfer Objects Guide](https://docs.spryker.com/docs/scos/dev/back-end-development/data-manipulation/data-ingestion/structural-preparations/create-use-and-extend-the-transfer-objects.html)
- [Facade Pattern](https://docs.spryker.com/docs/scos/dev/back-end-development/zed/business-layer/facade.html)
