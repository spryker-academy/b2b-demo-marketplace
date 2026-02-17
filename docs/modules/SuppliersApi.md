# SuppliersApi Module (Glue Storefront)

## Overview

The **SuppliersApi** module provides RESTful API endpoints for the Glue Storefront layer. It uses API Platform to expose supplier data to frontend applications, mobile apps, and external consumers.

## Purpose

- Expose supplier data via REST API
- Provide collection and single-item endpoints
- Handle API request/response transformation
- Support pagination, filtering, and sorting
- Integrate with Client layer for data access

## Architecture

```
HTTP Request: GET /suppliers
    ↓
API Platform Routing
    ↓
SuppliersStorefrontProvider::provide()
    ↓
SupplierClient (facade)
    ├─→ getSuppliers() → SupplierSearchClient → Elasticsearch
    └─→ findSupplierById() → SupplierStub → ZedRequest → Gateway
    ↓
SupplierMapper
    ↓
SuppliersStorefrontResource (API Platform DTO)
    ↓
JSON Response
```

---

## Module Structure

```
Glue/SuppliersApi/
├── Api/
│   └── Storefront/
│       └── Provider/
│           └── SuppliersStorefrontProvider.php  # Request handler
├── Processor/
│   └── Mapper/
│       └── SupplierMapper.php                    # Transfer → Resource
├── resources/
│   └── api/
│       └── storefront/
│           └── suppliers.resource.yml            # API definition
└── SuppliersApiConfig.php                        # Configuration
```

---

## Components

### 1. API Resource Definition (YAML)

**File**: `resources/api/storefront/suppliers.resource.yml`

```yaml
resource:
    name: Suppliers
    shortName: Supplier
    description: Supplier management API for storefront operations

    provider: SprykerAcademy\Glue\SuppliersApi\Api\Storefront\Provider\SuppliersStorefrontProvider

    paginationEnabled: true
    paginationItemsPerPage: 10

    operations:
        - type: Get              # GET /suppliers/{id}
        - type: GetCollection    # GET /suppliers

    properties:
        idSupplier:
            type: int
            description: The unique supplier identifier
            identifier: true      # REQUIRED for IRI generation
        name:
            type: string
            description: Supplier name
        description:
            type: string
            description: Supplier description
        status:
            type: int
            description: Supplier status (1=active, 0=inactive)
        email:
            type: string
            description: Supplier email
        phone:
            type: string
            description: Supplier phone
```

**Key Concepts**:
- **provider**: The PHP class that handles requests
- **operations**: Supported HTTP methods (Get, GetCollection, Post, Put, Patch, Delete)
- **properties**: API resource fields (NOT database fields)
- **identifier: true**: Required on ONE property for IRI generation

**Best Practices**:
- Keep `shortName` singular
- Keep `name` plural
- Add meaningful descriptions
- ONE property must have `identifier: true`
- Enable pagination for collections
- Document all properties

**After Modifying**:
```bash
docker/sdk cli console glue api:generate
```

### 2. SuppliersStorefrontProvider

**File**: `Api/Storefront/Provider/SuppliersStorefrontProvider.php`

**Purpose**: Handles incoming API requests and returns responses.

```php
class SuppliersStorefrontProvider implements ProviderInterface
{
    public function __construct(
        protected SupplierClientInterface $supplierClient,
        protected SupplierMapper $supplierMapper,
    ) {}

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): object|array|null {
        $idSupplier = $uriVariables['idSupplier'] ?? null;

        // Collection endpoint: GET /suppliers
        if ($idSupplier === null) {
            return $this->provideCollection($context);
        }

        // Validation
        if (!is_numeric($idSupplier) || (int)$idSupplier <= 0) {
            throw new BadRequestHttpException(
                sprintf('Invalid supplier ID: %s', $idSupplier)
            );
        }

        // Single item endpoint: GET /suppliers/{id}
        $supplierTransfer = $this->supplierClient->findSupplierById((int)$idSupplier);

        // 404 if not found
        if ($supplierTransfer->getIdSupplier() === null) {
            return null;
        }

        return $this->supplierMapper
            ->mapSupplierTransferToSuppliersStorefrontResource($supplierTransfer);
    }

    protected function provideCollection(array $context): array
    {
        $requestParameters = $this->extractRequestParameters($context);

        $supplierCollection = $this->supplierClient->getSuppliers($requestParameters);

        $resources = [];
        foreach ($supplierCollection->getSuppliers() as $supplierTransfer) {
            $resources[] = $this->supplierMapper
                ->mapSupplierTransferToSuppliersStorefrontResource($supplierTransfer);
        }

        return $resources;
    }

    protected function extractRequestParameters(array $context): array
    {
        $request = $context['request'] ?? null;
        if (!$request) {
            return [];
        }

        return [
            'page' => $request->query->get('page', 1),
            'ipp' => $request->query->get('ipp', 10),
            'q' => $request->query->get('q'),
        ];
    }
}
```

**Flow**:
1. Check if `idSupplier` is in URI variables
2. If null → Collection endpoint
3. If present → Validate → Single item endpoint
4. Load data via Client
5. Map Transfer to Resource
6. Return (API Platform handles JSON serialization)

**Return Values**:
- **Single item**: Resource object or `null` (404)
- **Collection**: Array of Resource objects
- **Error**: Throw exception (BadRequestHttpException, etc.)

**Best Practices**:
- Inject Client via constructor (from ApplicationServices.php)
- Validate input (ID format, ranges, etc.)
- Return `null` for 404 (API Platform handles it)
- Throw exceptions for 400/500 errors
- Extract request params from context
- Map Transfers to Resources

**Common Exceptions**:
```php
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

// 400 Bad Request
throw new BadRequestHttpException('Invalid input');

// 404 Not Found
throw new NotFoundHttpException('Supplier not found');

// 401 Unauthorized
throw new UnauthorizedHttpException('Bearer', 'Authentication required');
```

### 3. SupplierMapper

**File**: `Processor/Mapper/SupplierMapper.php`

**Purpose**: Converts Transfer objects to API Platform Resource objects.

```php
class SupplierMapper
{
    public function mapSupplierTransferToSuppliersStorefrontResource(
        SupplierTransfer $supplierTransfer,
    ): SuppliersStorefrontResource {
        // CRITICAL: Use toArray(false, true)
        // false = no recursion
        // true = camelCase keys (idSupplier, not id_supplier)
        return SuppliersStorefrontResource::fromArray(
            $supplierTransfer->toArray(false, true)
        );
    }
}
```

**Critical Point**: `toArray(false, true)`
- First param `false`: Don't recurse into nested transfers
- Second param `true`: Use **camelCase** keys

**Why camelCase?**
API Platform Resource properties are camelCased:
```php
class SuppliersStorefrontResource
{
    public int $idSupplier;    // camelCase
    public string $name;
}
```

If you use `toArray()` (default), it produces snake_case (`id_supplier`), which won't match.

**Best Practices**:
- One method per Transfer → Resource mapping
- Use `toArray(false, true)` for camelCase
- Keep mapper simple (just conversion)
- Don't add business logic here

### 4. Generated Resource Class

**File**: `vendor/spryker/... or generated/`

```php
// Generated by: glue api:generate
namespace Generated\Api\Storefront;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    shortName: 'Supplier',
    operations: [...]
)]
class SuppliersStorefrontResource
{
    public ?int $idSupplier = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?int $status = null;
    public ?string $email = null;
    public ?string $phone = null;
}
```

**Best Practices**:
- Never edit generated files manually
- Regenerate after YAML changes
- Properties match YAML definition
- All properties should be nullable

---

## Configuration

### 1. ApplicationServices.php

**File**: `config/GlueStorefront/ApplicationServices.php`

**Purpose**: Register services in Symfony DI container.

```php
use SprykerAcademy\Client\Supplier\SupplierClient;
use SprykerAcademy\Client\Supplier\SupplierClientInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()->autowire()->public()->autoconfigure();

    // Register Client (Spryker kernel handles Factory/DependencyProvider)
    $services->set(SupplierClientInterface::class, SupplierClient::class);

    // Mapper is auto-wired (no registration needed if simple)
    // Providers are auto-discovered via YAML
};
```

**What to Register**:
- ✅ Client interfaces/classes
- ✅ Complex services with dependencies
- ❌ Mappers (auto-wired)
- ❌ Providers (auto-discovered via YAML)

**Best Practices**:
- Register interface → implementation
- Let Spryker kernel handle Client internals
- Don't register every class
- Use autowiring when possible

### 2. API Platform Config

**File**: `config/GlueStorefront/packages/spryker_api_platform.php`

```php
$sprykerApiPlatform->sourceDirectories([
    'src/Pyz',
    'src/SprykerAcademy',   // YOUR namespace must be listed
    'vendor/spryker',
    'vendor/spryker-shop',
    'vendor/spryker-feature',
]);
```

**Purpose**: Tells API Platform where to find resource YAML files.

**Best Practices**:
- Add your namespace
- Keep vendor paths
- Rebuild after changes

---

## API Endpoints

### Collection Endpoint

**Request**:
```bash
GET /suppliers
GET /suppliers?page=2&ipp=5
GET /suppliers?q=acme
```

**Response**:
```json
{
  "data": [
    {
      "type": "suppliers",
      "id": "1",
      "attributes": {
        "idSupplier": 1,
        "name": "ACME Corporation",
        "description": "Industrial supplier",
        "status": 1,
        "email": "contact@acme.com",
        "phone": "+1234567890"
      }
    },
    {
      "type": "suppliers",
      "id": "2",
      "attributes": {...}
    }
  ]
}
```

### Single Item Endpoint

**Request**:
```bash
GET /suppliers/1
```

**Response**:
```json
{
  "data": {
    "type": "suppliers",
    "id": "1",
    "attributes": {
      "idSupplier": 1,
      "name": "ACME Corporation",
      "description": "Industrial supplier",
      "status": 1,
      "email": "contact@acme.com",
      "phone": "+1234567890"
    }
  }
}
```

### Error Response (404)

**Request**:
```bash
GET /suppliers/99999
```

**Response**:
```json
{
  "errors": [{
    "status": "404",
    "detail": "Not found"
  }]
}
```

---

## Request/Response Flow

### GET /suppliers (Collection)

```
1. HTTP Request: GET /suppliers?page=1&ipp=10
    ↓
2. API Platform routing matches
    ↓
3. SuppliersStorefrontProvider::provide(operation, [], context)
    ↓
4. extractRequestParameters(context) → ['page' => 1, 'ipp' => 10]
    ↓
5. SupplierClient::getSuppliers(['page' => 1, 'ipp' => 10])
    ↓
6. SupplierSearchClient::searchSuppliers()
    ↓
7. Elasticsearch query → hits
    ↓
8. Returns SupplierCollectionTransfer
    ↓
9. Loop through transfers
    ↓
10. SupplierMapper::mapSupplierTransferToSuppliersStorefrontResource()
    ↓
11. Return array of SuppliersStorefrontResource
    ↓
12. API Platform serializes to JSON
    ↓
13. HTTP Response: 200 OK with JSON body
```

### GET /suppliers/{id} (Single Item)

```
1. HTTP Request: GET /suppliers/1
    ↓
2. API Platform extracts: uriVariables = ['idSupplier' => '1']
    ↓
3. SuppliersStorefrontProvider::provide(operation, ['idSupplier' => '1'], context)
    ↓
4. Validate ID
    ↓
5. SupplierClient::findSupplierById(1)
    ↓
6. SupplierStub::findSupplierById() [ZedRequest RPC]
    ↓
7. GatewayController::findSupplierByIdAction()
    ↓
8. Returns SupplierTransfer
    ↓
9. SupplierMapper::mapSupplierTransferToSuppliersStorefrontResource()
    ↓
10. Return SuppliersStorefrontResource
    ↓
11. API Platform serializes to JSON
    ↓
12. HTTP Response: 200 OK with JSON body
```

---

## Testing

### Manual Testing

```bash
# Collection
curl http://glue-storefront.eu.spryker.local/suppliers | jq

# With pagination
curl http://glue-storefront.eu.spryker.local/suppliers?page=1&ipp=5 | jq

# Single item
curl http://glue-storefront.eu.spryker.local/suppliers/1 | jq

# Not found
curl http://glue-storefront.eu.spryker.local/suppliers/99999 -i
```

### Integration Tests

```php
class SuppliersRestApiTest extends \Codeception\Test\Unit
{
    protected SuppliersApiTester $tester;

    public function testGetSupplierCollection(): void
    {
        // Arrange
        $this->tester->haveSupplierInElasticsearch([
            'idSupplier' => 1,
            'name' => 'Test Supplier',
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
                        'name' => 'Test Supplier',
                    ],
                ],
            ],
        ]);
    }

    public function testGetSupplierById(): void
    {
        // Arrange
        $supplier = $this->tester->haveSupplier([
            'name' => 'Test Supplier',
        ]);

        // Act
        $this->tester->sendGet('/suppliers/' . $supplier->getIdSupplier());

        // Assert
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseMatchesJsonType([
            'data' => [
                'type' => 'string',
                'id' => 'string',
                'attributes' => [
                    'name' => 'string',
                    'idSupplier' => 'integer',
                ],
            ],
        ]);
    }

    public function testGetNonExistentSupplierReturns404(): void
    {
        $this->tester->sendGet('/suppliers/99999');
        $this->tester->seeResponseCodeIs(404);
    }
}
```

---

## Common Issues & Solutions

### Issue: "Unable to generate an IRI"

**Cause**: No property has `identifier: true` in YAML

**Solution**:
```yaml
properties:
    idSupplier:
        type: int
        identifier: true  # ADD THIS
```

### Issue: Properties are null in JSON response

**Cause**: Using wrong `toArray()` parameters

**Solution**:
```php
// WRONG
$supplierTransfer->toArray()  // snake_case keys

// CORRECT
$supplierTransfer->toArray(false, true)  // camelCase keys
```

### Issue: 404 Not Found for /suppliers

**Cause**: Routes not cached

**Solution**:
```bash
docker/sdk cli console cache:empty-all
docker/sdk cli console router:cache:clear
```

### Issue: "Could not find X in any of the attached containers"

**Cause**: Client not registered in ApplicationServices.php

**Solution**:
```php
$services->set(SupplierClientInterface::class, SupplierClient::class);
```

### Issue: Changes to YAML not reflected

**Cause**: Resource class not regenerated

**Solution**:
```bash
docker/sdk cli console glue api:generate
```

---

## Best Practices Summary

### DO ✅

1. **Validate Input**
   ```php
   if (!is_numeric($id) || $id <= 0) {
       throw new BadRequestHttpException('Invalid ID');
   }
   ```

2. **Return Null for 404**
   ```php
   if ($transfer->getIdSupplier() === null) {
       return null;  // API Platform handles 404
   }
   ```

3. **Use camelCase in Mapper**
   ```php
   $transfer->toArray(false, true)  // camelCase
   ```

4. **Set identifier: true**
   ```yaml
   idSupplier:
       identifier: true
   ```

5. **Extract Request Params**
   ```php
   $page = $context['request']->query->get('page', 1);
   ```

### DON'T ❌

1. **Don't Use Snake Case**
   ```php
   // WRONG
   $transfer->toArray()  // id_supplier won't match

   // RIGHT
   $transfer->toArray(false, true)  // idSupplier matches
   ```

2. **Don't Return Null from Collection**
   ```php
   // WRONG
   return null;

   // RIGHT
   return [];  // Empty array for empty collection
   ```

3. **Don't Access Database Directly**
   ```php
   // WRONG
   PyzSupplierQuery::create()->find();

   // RIGHT
   $this->supplierClient->getSuppliers();
   ```

4. **Don't Skip Validation**
   ```php
   // WRONG
   $id = $uriVariables['id'];  // Could be non-numeric

   // RIGHT
   if (!is_numeric($id)) throw new BadRequestHttpException();
   ```

5. **Don't Forget Registration**
   ```php
   // In ApplicationServices.php
   $services->set(SupplierClientInterface::class, SupplierClient::class);
   ```

---

## Related Modules

- **Supplier (Client)**: Data access layer
- **SupplierSearch (Client)**: Elasticsearch queries
- **Glue**: API Platform framework
- **API Platform**: REST API generation

---

## Further Reading

- [API Platform Documentation](https://api-platform.com/docs/)
- [Spryker Glue API](https://docs.spryker.com/docs/scos/dev/glue-api-guides/glue-rest-api.html)
- [API Platform in Spryker](https://docs.spryker.com/docs/dg/dev/glue-api/202404.0/decoupled-glue-api/api-platform.html)
