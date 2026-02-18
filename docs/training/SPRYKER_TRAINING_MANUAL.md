# Spryker Backend Development Training Manual
## Version 202512.0 - Complete Developer Guide

> **Target Audience:** Backend Developers learning Spryker  
> **Duration:** 3-5 days intensive training  
> **Prerequisites:** PHP 8.1+, OOP, MVC, Basic SQL, Docker

---

## 📚 Table of Contents

### Part 1: Basics (Foundation) - Day 1-2

1. [Hello World Back Office](#chapter-1-hello-world-back-office) - 30 minutes
2. [Data Transfer Objects](#chapter-2-data-transfer-objects) - 45 minutes  
3. [Database Schema - Message](#chapter-3-database-schema-message) - 1 hour
4. [Database Schema - Supplier](#chapter-4-database-schema-supplier) - 1 hour
5. [Module Layers Architecture](#chapter-5-module-layers-architecture) - 2 hours

### Part 2: Intermediate (Real-World Features) - Day 3-5

6. [Back Office CRUD](#chapter-6-back-office-crud) - 3 hours
7. [Data Import](#chapter-7-data-import) - 2 hours
8. [Publish & Synchronize](#chapter-8-publish--synchronize) - 3 hours
9. [Elasticsearch Integration](#chapter-9-elasticsearch-integration) - 3 hours
10. [API Platform (Glue)](#chapter-10-api-platform-glue) - 3 hours
11. [Order Management System](#chapter-11-order-management-system) - 4 hours

---

## 🎯 Training Approach

Each chapter follows this structure:
1. **Skeleton Branch** - Work on TODOs
2. **Implementation** - Follow step-by-step guide
3. **Complete Branch** - Compare your solution
4. **Testing** - Verify functionality
5. **Deep Dive** - Understand concepts

---

# Part 1: Basics

## Chapter 1: Hello World Back Office

**Branch Pattern:** `ilt/202512.0/basics/hello-world-back-office/{skeleton|complete}`  
**Time:** 30 minutes  
**Difficulty:** ⭐☆☆☆☆

### What You'll Learn
- Spryker module structure
- Zed layer (Back Office)
- Controllers and actions
- Twig templating

### Spryker Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│                    Spryker Layers                       │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐            │
│  │   Yves   │  │   Zed    │  │  Glue    │            │
│  │(Frontend)│  │(Backend) │  │  (API)   │            │
│  └──────────┘  └──────────┘  └──────────┘            │
│       │             │              │                   │
│       └─────────────┴──────────────┘                   │
│                    │                                   │
│            ┌───────▼────────┐                         │
│            │     Client      │                         │
│            │  (RPC Bridge)   │                         │
│            └─────────────────┘                         │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

### Step-by-Step: Create Hello World

#### 1. Create Module Structure

```bash
mkdir -p src/Pyz/Zed/HelloWorld/Communication/Controller
mkdir -p src/Pyz/Zed/HelloWorld/Presentation/Index
```

**Directory Structure:**
```
src/Pyz/Zed/HelloWorld/
├── Communication/
│   └── Controller/
│       └── IndexController.php
└── Presentation/
    └── Index/
        └── index.twig
```

#### 2. Implement Controller

**File:** `src/Pyz/Zed/HelloWorld/Communication/Controller/IndexController.php`

```php
<?php

namespace Pyz\Zed\HelloWorld\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        return [
            'message' => 'Hello World from Spryker!',
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }
}
```

**Key Concepts:**
- Extends `AbstractController` from Spryker Kernel
- Method name ends with `Action` (routable)
- Returns array (passed to Twig automatically)
- Type hints for clarity

#### 3. Create Twig Template

**File:** `src/Pyz/Zed/HelloWorld/Presentation/Index/index.twig`

```twig
{% extends '@Gui/Layout/layout.twig' %}

{% block content %}
    <div class="spy-card">
        <div class="spy-card__header">
            <h2>Hello World Module</h2>
        </div>
        <div class="spy-card__body">
            <p class="text-success">
                <strong>{{ message }}</strong>
            </p>
            <p class="text-muted">
                Generated at: {{ timestamp }}
            </p>
        </div>
    </div>
{% endblock %}
```

**Key Concepts:**
- Extends Back Office layout (`@Gui/Layout/layout.twig`)
- Uses Spryker UI components (`spy-card`)
- Accesses controller variables directly (`{{ message }}`)

#### 4. Test Your Implementation

**URL:** `http://backoffice.eu.spryker.local/hello-world`

**Expected Result:**
- Back Office navigation and header
- Card displaying "Hello World from Spryker!"
- Current timestamp

### URL Routing in Spryker

```
URL Pattern: /{module}/{controller}/{action}/{parameters}

Example: /hello-world/index/index
          └─module   └controller └action

Defaults:
- Missing controller = IndexController
- Missing action = indexAction

So /hello-world maps to:
  Pyz\Zed\HelloWorld\Communication\Controller\IndexController::indexAction()
```

### Best Practices

✅ **DO:**
- Follow PSR-12 coding standards
- Use type hints for parameters and return types
- Keep controllers thin (no business logic)
- Return arrays from actions

❌ **DON'T:**
- Put business logic in controllers
- Use `echo`, `print`, `die()` in controllers
- Modify response headers directly
- Access database from controllers

### Common Issues

| Issue | Solution |
|-------|----------|
| 404 Not Found | Check namespace and directory structure match |
| Method not found | Ensure method ends with `Action` |
| Template not found | Check Presentation path matches controller/action |
| Blank page | Check Symfony error logs in `var/logs/` |

---

## Chapter 2: Data Transfer Objects

**Branch Pattern:** `ilt/202512.0/basics/data-transfer-object/{skeleton|complete}`  
**Time:** 45 minutes  
**Difficulty:** ⭐⭐☆☆☆

### What You'll Learn
- Transfer Object pattern in Spryker
- XML schema definitions
- Code generation
- Type-safe data handling

### Why Transfer Objects?

**Problem:**
```php
// Bad: Arrays are not type-safe
function processOrder(array $data) {
    $id = $data['order_id']; // What if key doesn't exist?
    $items = $data['items']; // What structure is this?
}
```

**Solution:**
```php
// Good: Transfer Objects are strongly typed
function processOrder(OrderTransfer $orderTransfer) {
    $id = $orderTransfer->getOrderReference(); // IDE autocomplete
    $items = $orderTransfer->getItems(); // Type-safe ItemTransfer[]
}
```

### Transfer Object Lifecycle

```
1. Define Schema (XML)
   └─> src/Pyz/Shared/{Module}/Transfer/{module}.transfer.xml

2. Generate Classes
   └─> console transfer:generate

3. Use Transfer Objects
   └─> Generated in src/Generated/Shared/Transfer/

4. Modify & Regenerate
   └─> Add properties, run transfer:generate again
```

### Step-by-Step: Create Message Transfer

#### 1. Define Transfer Schema

**File:** `src/Pyz/Shared/Message/Transfer/message.transfer.xml`

```xml
<?xml version="1.0"?>
<transfers xmlns="spryker:transfer-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">

    <transfer name="Message">
        <property name="idMessage" type="int"/>
        <property name="text" type="string"/>
        <property name="author" type="string"/>
        <property name="createdAt" type="string"/>
        <property name="updatedAt" type="string"/>
    </transfer>

</transfers>
```

#### 2. Generate Transfer Classes

```bash
console transfer:generate
```

**Generated:** `src/Generated/Shared/Transfer/MessageTransfer.php`

**What Gets Generated:**
```php
class MessageTransfer extends AbstractTransfer
{
    protected $idMessage;
    protected $text;
    protected $author;
    protected $createdAt;
    protected $updatedAt;

    public function getIdMessage(): ?int { ... }
    public function setIdMessage(?int $idMessage): self { ... }
    public function getText(): ?string { ... }
    public function setText(?string $text): self { ... }
    // ... more getters/setters

    public function toArray(): array { ... }
    public function fromArray(array $data): self { ... }
    public function requireText(): self { ... }
}
```

#### 3. Use Transfer in Controller

**File:** `src/Pyz/Zed/Message/Communication/Controller/IndexController.php`

```php
<?php

namespace Pyz\Zed\Message\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        // Create new transfer
        $messageTransfer = (new MessageTransfer())
            ->setText('Welcome to Transfer Objects!')
            ->setAuthor('Spryker Training')
            ->setCreatedAt(date('Y-m-d H:i:s'));

        // Convert to array
        $messageArray = $messageTransfer->toArray();

        // Create from array
        $newTransfer = (new MessageTransfer())->fromArray($messageArray);

        return [
            'message' => $messageTransfer,
            'array' => $messageArray,
        ];
    }
}
```

### Transfer Property Types

| XML Type | PHP Type | Example | Notes |
|----------|----------|---------|-------|
| `int` | `int` | `<property name="id" type="int"/>` | Integer |
| `string` | `string` | `<property name="name" type="string"/>` | String |
| `bool` | `bool` | `<property name="isActive" type="bool"/>` | Boolean |
| `float` | `float` | `<property name="price" type="float"/>` | Decimal |
| `array` | `array` | `<property name="data" type="array"/>` | Generic array |
| Transfer | Transfer | `<property name="customer" type="Customer"/>` | Nested transfer |
| Transfer[] | array | `<property name="items" type="Item[]" singular="item"/>` | Array of transfers |

### Advanced: Nested Transfers

```xml
<transfer name="Order">
    <property name="orderReference" type="string"/>
    <property name="customer" type="Customer"/>
    <property name="items" type="Item[]" singular="item"/>
</transfer>

<transfer name="Customer">
    <property name="firstName" type="string"/>
    <property name="lastName" type="string"/>
</transfer>

<transfer name="Item">
    <property name="sku" type="string"/>
    <property name="quantity" type="int"/>
</transfer>
```

**Usage:**
```php
$orderTransfer = (new OrderTransfer())
    ->setOrderReference('DE--1')
    ->setCustomer(
        (new CustomerTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
    )
    ->addItem(
        (new ItemTransfer())->setSku('SKU-001')->setQuantity(2)
    )
    ->addItem(
        (new ItemTransfer())->setSku('SKU-002')->setQuantity(1)
    );

// Access nested data
$customerName = $orderTransfer->getCustomer()->getFirstName();
$firstItem = $orderTransfer->getItems()[0];
```

### Transfer Object Methods

```php
$transfer = new MessageTransfer();

// Setters (fluent interface)
$transfer->setText('Hello')->setAuthor('John');

// Getters
$text = $transfer->getText();

// Array conversion
$array = $transfer->toArray();
$transfer->fromArray(['text' => 'Hello']);

// Validation
$transfer->requireText(); // Throws exception if not set

// Check if set
$hasText = $transfer->getText() !== null;

// Modification tracking
$modified = $transfer->modifiedToArray(); // Only changed properties
```

### Best Practices

✅ **DO:**
- Always regenerate after schema changes
- Define transfers in `Shared` layer
- Use descriptive property names
- Group related transfers in same XML

❌ **DON'T:**
- Modify generated classes
- Use arrays for structured data
- Forget to run `transfer:generate`
- Put business logic in transfers

---

## Chapter 3: Database Schema - Message Table

**Branch Pattern:** `ilt/202512.0/basics/message-table-schema/{skeleton|complete}`  
**Time:** 1 hour  
**Difficulty:** ⭐⭐☆☆☆

### What You'll Learn
- Propel ORM schema definitions
- Database migrations
- Entity and Query classes
- CRUD operations

### Propel Schema to Database Flow

```
┌─────────────────────────────────────┐
│  Schema Definition (XML)            │
│  Persistence/Propel/Schema/         │
└──────────────┬──────────────────────┘
               │
               │ console propel:install
               ▼
┌─────────────────────────────────────┐
│  Generated Models                   │
│  Orm/Zed/{Module}/Persistence/      │
│  - SpyMessage.php (Entity)          │
│  - SpyMessageQuery.php (Query)      │
└──────────────┬──────────────────────┘
               │
               │ console propel:migrate
               ▼
┌─────────────────────────────────────┐
│  Database Table                     │
│  pyz_message                        │
└─────────────────────────────────────┘
```

### Step-by-Step Implementation

#### 1. Create Schema Definition

**File:** `src/Pyz/Zed/Message/Persistence/Propel/Schema/pyz_message.schema.xml`

```xml
<?xml version="1.0"?>
<database xmlns="spryker:schema-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    name="zed"
    xsi:schemaLocation="spryker:schema-01 https://static.spryker.com/schema-01.xsd"
    namespace="Orm\Zed\Message\Persistence"
    package="src.Orm.Zed.Message.Persistence">

    <table name="pyz_message" idMethod="native" allowPkInsert="true">
        <column name="id_message" type="INTEGER" primaryKey="true" autoIncrement="true"/>
        <column name="text" type="LONGVARCHAR" required="true"/>
        <column name="author" type="VARCHAR" size="255"/>
        <column name="created_at" type="TIMESTAMP"/>
        <column name="updated_at" type="TIMESTAMP"/>

        <id-method-parameter value="pyz_message_pk_seq"/>

        <behavior name="timestampable">
            <parameter name="create_column" value="created_at"/>
            <parameter name="update_column" value="updated_at"/>
        </behavior>
    </table>

</database>
```

#### 2. Generate Models and Run Migration

```bash
# Generate Propel models
console propel:install

# Run database migration
console propel:migrate
```

#### 3. Use Entity Classes

**Create Record:**
```php
use Orm\Zed\Message\Persistence\SpyMessage;

$messageEntity = new SpyMessage();
$messageEntity->setText('Hello from database!')
    ->setAuthor('John Doe')
    ->save();

$id = $messageEntity->getIdMessage();
```

**Query Records:**
```php
use Orm\Zed\Message\Persistence\SpyMessageQuery;

// Find all
$messages = SpyMessageQuery::create()->find();

// Find by ID
$message = SpyMessageQuery::create()
    ->findOneByIdMessage($id);

// Find with filter
$recentMessages = SpyMessageQuery::create()
    ->filterByAuthor('John Doe')
    ->orderByCreatedAt('DESC')
    ->limit(10)
    ->find();
```

**Update Record:**
```php
$message = SpyMessageQuery::create()
    ->findOneByIdMessage($id);

$message->setText('Updated text')
    ->save();
```

**Delete Record:**
```php
$message = SpyMessageQuery::create()
    ->findOneByIdMessage($id);

$message->delete();
```

### Schema Column Types

| Propel Type | MySQL Type | PHP Type | Description |
|------------|-----------|----------|-------------|
| `INTEGER` | INT | int | Whole numbers |
| `VARCHAR` | VARCHAR | string | Variable-length string |
| `LONGVARCHAR` | TEXT | string | Long text |
| `TIMESTAMP` | TIMESTAMP | string | Date and time |
| `BOOLEAN` | TINYINT | bool | True/false |
| `DECIMAL` | DECIMAL | float | Decimal numbers |

### Propel Behaviors

**Timestampable:**
```xml
<behavior name="timestampable">
    <parameter name="create_column" value="created_at"/>
    <parameter name="update_column" value="updated_at"/>
</behavior>
```

**Event:**
```xml
<behavior name="event">
    <parameter name="pyz_message_all" column="*"/>
</behavior>
```

---

# Spryker Training Manual - Part 2: Intermediate Topics

## Chapter 4: Database Schema - Supplier Table

**Branch Pattern:** `ilt/202512.0/basics/supplier-table-schema/{skeleton|complete}`  
**Time:** 1 hour  
**Difficulty:** ⭐⭐⭐☆☆

### What You'll Learn
- Complex schema with relationships
- Foreign keys and relations
- Multiple table definitions
- Propel entity relationships

### Supplier Domain Model

```
┌─────────────────────┐         ┌──────────────────────────┐
│   pyz_supplier      │         │  pyz_supplier_location   │
├─────────────────────┤         ├──────────────────────────┤
│ id_supplier (PK)    │────<    │ id_supplier_location (PK)│
│ name                │         │ fk_supplier (FK)         │
│ email               │         │ address                  │
│ phone               │         │ city                     │
│ is_active           │         │ country                  │
│ created_at          │         │ zip_code                 │
│ updated_at          │         │ is_primary               │
└─────────────────────┘         └──────────────────────────┘
```

### Step-by-Step Implementation

#### 1. Create Supplier Schema

**File:** `src/Pyz/Zed/Supplier/Persistence/Propel/Schema/pyz_supplier.schema.xml`

```xml
<?xml version="1.0"?>
<database xmlns="spryker:schema-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    name="zed"
    xsi:schemaLocation="spryker:schema-01 https://static.spryker.com/schema-01.xsd"
    namespace="Orm\Zed\Supplier\Persistence"
    package="src.Orm.Zed.Supplier.Persistence">

    <table name="pyz_supplier" idMethod="native">
        <column name="id_supplier" type="INTEGER" primaryKey="true" autoIncrement="true"/>
        <column name="name" type="VARCHAR" size="255" required="true"/>
        <column name="email" type="VARCHAR" size="255"/>
        <column name="phone" type="VARCHAR" size="50"/>
        <column name="is_active" type="BOOLEAN" default="true"/>
        <column name="created_at" type="TIMESTAMP"/>
        <column name="updated_at" type="TIMESTAMP"/>

        <unique name="pyz_supplier-unique-name">
            <unique-column name="name"/>
        </unique>

        <index name="pyz_supplier-is_active">
            <index-column name="is_active"/>
        </index>

        <id-method-parameter value="pyz_supplier_pk_seq"/>

        <behavior name="timestampable">
            <parameter name="create_column" value="created_at"/>
            <parameter name="update_column" value="updated_at"/>
        </behavior>

        <behavior name="event">
            <parameter name="pyz_supplier_all" column="*"/>
        </behavior>
    </table>

    <table name="pyz_supplier_location" idMethod="native">
        <column name="id_supplier_location" type="INTEGER" primaryKey="true" autoIncrement="true"/>
        <column name="fk_supplier" type="INTEGER" required="true"/>
        <column name="address" type="VARCHAR" size="500" required="true"/>
        <column name="city" type="VARCHAR" size="100" required="true"/>
        <column name="country" type="VARCHAR" size="2" required="true"/>
        <column name="zip_code" type="VARCHAR" size="20" required="true"/>
        <column name="is_primary" type="BOOLEAN" default="false"/>

        <foreign-key name="pyz_supplier_location-fk-supplier" foreignTable="pyz_supplier">
            <reference local="fk_supplier" foreign="id_supplier"/>
        </foreign-key>

        <index name="pyz_supplier_location-fk_supplier">
            <index-column name="fk_supplier"/>
        </index>

        <id-method-parameter value="pyz_supplier_location_pk_seq"/>
    </table>

</database>
```

#### 2. Generate and Migrate

```bash
console propel:install
console propel:migrate
```

#### 3. Working with Relationships

**Create Supplier with Locations:**
```php
use Orm\Zed\Supplier\Persistence\SpySupplier;
use Orm\Zed\Supplier\Persistence\SpySupplierLocation;

$supplier = new SpySupplier();
$supplier->setName('ACME Corp')
    ->setEmail('contact@acme.com')
    ->setPhone('+1234567890')
    ->setIsActive(true);

// Add primary location
$primaryLocation = new SpySupplierLocation();
$primaryLocation->setAddress('123 Main St')
    ->setCity('New York')
    ->setCountry('US')
    ->setZipCode('10001')
    ->setIsPrimary(true);

$supplier->addSpySupplierLocation($primaryLocation);

// Add secondary location
$secondaryLocation = new SpySupplierLocation();
$secondaryLocation->setAddress('456 Oak Ave')
    ->setCity('Los Angeles')
    ->setCountry('US')
    ->setZipCode('90001')
    ->setIsPrimary(false);

$supplier->addSpySupplierLocation($secondaryLocation);

$supplier->save(); // Saves supplier and all locations
```

**Query with Relations:**
```php
use Orm\Zed\Supplier\Persistence\SpySupplierQuery;

// Find supplier with locations
$supplier = SpySupplierQuery::create()
    ->filterByIsActive(true)
    ->findOneByIdSupplier($id);

// Access locations
$locations = $supplier->getSpySupplierLocations();
foreach ($locations as $location) {
    echo $location->getAddress() . PHP_EOL;
}

// Query with join
$suppliersWithLocations = SpySupplierQuery::create()
    ->joinWithSpySupplierLocation()
    ->find();
```

### Schema Features Explained

**Unique Constraint:**
```xml
<unique name="pyz_supplier-unique-name">
    <unique-column name="name"/>
</unique>
```
Ensures no duplicate supplier names.

**Index:**
```xml
<index name="pyz_supplier-is_active">
    <index-column name="is_active"/>
</index>
```
Speeds up queries filtering by `is_active`.

**Foreign Key:**
```xml
<foreign-key name="pyz_supplier_location-fk-supplier" foreignTable="pyz_supplier">
    <reference local="fk_supplier" foreign="id_supplier"/>
</foreign-key>
```
Establishes one-to-many relationship.

**Event Behavior:**
```xml
<behavior name="event">
    <parameter name="pyz_supplier_all" column="*"/>
</behavior>
```
Triggers Spryker events on entity changes (used for Publish & Sync).

---

## Chapter 5: Module Layers Architecture

**Branch Pattern:** `ilt/202512.0/basics/module-layers/{skeleton|complete}`  
**Time:** 2 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### What You'll Learn
- Complete module structure
- All layers (Business, Communication, Persistence)
- Facade pattern
- Dependency injection

### Spryker Module Layers

```
┌──────────────────────────────────────────────────────────┐
│                    Zed Module                            │
├──────────────────────────────────────────────────────────┤
│                                                          │
│  Communication Layer (Controllers, Forms, Tables)        │
│  └─> Controller calls Facade                            │
│           │                                              │
│           ▼                                              │
│  Business Layer (Business Logic)                         │
│  ├─> Facade (Public API)                                │
│  ├─> Factory (Creates models)                           │
│  └─> Models (Business logic)                            │
│           │                                              │
│           ▼                                              │
│  Persistence Layer (Database Operations)                 │
│  ├─> Repository (Read operations)                       │
│  ├─> EntityManager (Write operations)                   │
│  └─> Factory (Creates repository/entity manager)        │
│           │                                              │
│           ▼                                              │
│  Propel Entities (Generated ORM)                        │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

### Complete Supplier Module Structure

```
src/Pyz/Zed/Supplier/
├── Business/
│   ├── SupplierFacade.php
│   ├── SupplierFacadeInterface.php
│   ├── SupplierBusinessFactory.php
│   ├── Reader/
│   │   ├── SupplierReader.php
│   │   └── SupplierReaderInterface.php
│   └── Writer/
│       ├── SupplierWriter.php
│       └── SupplierWriterInterface.php
├── Communication/
│   ├── Controller/
│   │   ├── IndexController.php
│   │   └── GatewayController.php
│   └── SupplierCommunicationFactory.php
├── Persistence/
│   ├── SupplierRepository.php
│   ├── SupplierRepositoryInterface.php
│   ├── SupplierEntityManager.php
│   ├── SupplierEntityManagerInterface.php
│   ├── SupplierPersistenceFactory.php
│   └── Propel/Schema/pyz_supplier.schema.xml
└── Presentation/
    └── Index/
        └── index.twig
```

### Step-by-Step: Build Complete Module

#### 1. Facade (Business Layer Public API)

**File:** `src/Pyz/Zed/Supplier/Business/SupplierFacadeInterface.php`

```php
<?php

namespace Pyz\Zed\Supplier\Business;

use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierFacadeInterface
{
    /**
     * @param int $idSupplier
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer|null
     */
    public function findSupplierById(int $idSupplier): ?SupplierTransfer;

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;
}
```

**File:** `src/Pyz/Zed/Supplier/Business/SupplierFacade.php`

```php
<?php

namespace Pyz\Zed\Supplier\Business;

use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\Supplier\Business\SupplierBusinessFactory getFactory()
 * @method \Pyz\Zed\Supplier\Persistence\SupplierRepositoryInterface getRepository()
 * @method \Pyz\Zed\Supplier\Persistence\SupplierEntityManagerInterface getEntityManager()
 */
class SupplierFacade extends AbstractFacade implements SupplierFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @param int $idSupplier
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer|null
     */
    public function findSupplierById(int $idSupplier): ?SupplierTransfer
    {
        return $this->getFactory()
            ->createSupplierReader()
            ->findSupplierById($idSupplier);
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        return $this->getFactory()
            ->createSupplierWriter()
            ->createSupplier($supplierTransfer);
    }
}
```

#### 2. Business Factory

**File:** `src/Pyz/Zed/Supplier/Business/SupplierBusinessFactory.php`

```php
<?php

namespace Pyz\Zed\Supplier\Business;

use Pyz\Zed\Supplier\Business\Reader\SupplierReader;
use Pyz\Zed\Supplier\Business\Reader\SupplierReaderInterface;
use Pyz\Zed\Supplier\Business\Writer\SupplierWriter;
use Pyz\Zed\Supplier\Business\Writer\SupplierWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\Supplier\Persistence\SupplierRepositoryInterface getRepository()
 * @method \Pyz\Zed\Supplier\Persistence\SupplierEntityManagerInterface getEntityManager()
 */
class SupplierBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\Supplier\Business\Reader\SupplierReaderInterface
     */
    public function createSupplierReader(): SupplierReaderInterface
    {
        return new SupplierReader($this->getRepository());
    }

    /**
     * @return \Pyz\Zed\Supplier\Business\Writer\SupplierWriterInterface
     */
    public function createSupplierWriter(): SupplierWriterInterface
    {
        return new SupplierWriter($this->getEntityManager());
    }
}
```

#### 3. Repository (Read Operations)

**File:** `src/Pyz/Zed/Supplier/Persistence/SupplierRepositoryInterface.php`

```php
<?php

namespace Pyz\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierRepositoryInterface
{
    /**
     * @param int $idSupplier
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer|null
     */
    public function findSupplierById(int $idSupplier): ?SupplierTransfer;
}
```

**File:** `src/Pyz/Zed/Supplier/Persistence/SupplierRepository.php`

```php
<?php

namespace Pyz\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\Supplier\Persistence\SupplierPersistenceFactory getFactory()
 */
class SupplierRepository extends AbstractRepository implements SupplierRepositoryInterface
{
    /**
     * @param int $idSupplier
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer|null
     */
    public function findSupplierById(int $idSupplier): ?SupplierTransfer
    {
        $supplierEntity = $this->getFactory()
            ->createSupplierQuery()
            ->findOneByIdSupplier($idSupplier);

        if ($supplierEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createSupplierMapper()
            ->mapSupplierEntityToSupplierTransfer($supplierEntity, new SupplierTransfer());
    }
}
```

#### 4. Entity Manager (Write Operations)

**File:** `src/Pyz/Zed/Supplier/Persistence/SupplierEntityManagerInterface.php`

```php
<?php

namespace Pyz\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer;
}
```

**File:** `src/Pyz/Zed/Supplier/Persistence/SupplierEntityManager.php`

```php
<?php

namespace Pyz\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\SpySupplier;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\Supplier\Persistence\SupplierPersistenceFactory getFactory()
 */
class SupplierEntityManager extends AbstractEntityManager implements SupplierEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        $supplierEntity = new SpySupplier();

        $supplierEntity = $this->getFactory()
            ->createSupplierMapper()
            ->mapSupplierTransferToSupplierEntity($supplierTransfer, $supplierEntity);

        $supplierEntity->save();

        return $this->getFactory()
            ->createSupplierMapper()
            ->mapSupplierEntityToSupplierTransfer($supplierEntity, $supplierTransfer);
    }
}
```

### Layer Responsibilities

| Layer | Responsibility | Examples |
|-------|---------------|----------|
| **Communication** | Handle HTTP requests, user input | Controllers, Forms, Tables |
| **Business** | Business logic, orchestration | Readers, Writers, Calculators |
| **Persistence** | Database operations | Repository, Entity Manager |

### Best Practices

✅ **DO:**
- Keep layers separate and focused
- Use Facade as single entry point to Business layer
- Implement interfaces for all public methods
- Use Factories for object creation
- Separate read (Repository) from write (EntityManager)

❌ **DON'T:**
- Skip layers (Controller → Persistence directly)
- Put business logic in Controllers
- Access EntityManager from Repository
- Mix concerns across layers

---

# Part 2: Intermediate

## Chapter 6: Back Office CRUD Operations

**Branch Pattern:** `ilt/202512.0/intermediate/back-office/{skeleton|complete}`  
**Time:** 3 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### What You'll Learn
- Create, Read, Update, Delete operations
- Symfony Forms integration
- Data tables with sorting/filtering
- Form validation

### CRUD Flow

```
List View (Table)
     │
     ├─> Create → Form → Save → Redirect to List
     │
     ├─> Edit → Form → Update → Redirect to List
     │
     └─> Delete → Confirmation → Delete → Redirect to List
```

### Step-by-Step: Supplier CRUD

#### 1. List View with Table

**Controller:** `src/Pyz/Zed/Supplier/Communication/Controller/IndexController.php`

```php
<?php

namespace Pyz\Zed\Supplier\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Pyz\Zed\Supplier\Communication\SupplierCommunicationFactory getFactory()
 * @method \Pyz\Zed\Supplier\Business\SupplierFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $supplierTable = $this->getFactory()->createSupplierTable();

        return $this->viewResponse([
            'supplierTable' => $supplierTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $supplierTable = $this->getFactory()->createSupplierTable();

        return $this->jsonResponse($supplierTable->fetchData());
    }
}
```

**Table Class:** `src/Pyz/Zed/Supplier/Communication/Table/SupplierTable.php`

```php
<?php

namespace Pyz\Zed\Supplier\Communication\Table;

use Orm\Zed\Supplier\Persistence\Map\SpySupplierTableMap;
use Orm\Zed\Supplier\Persistence\SpySupplierQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SupplierTable extends AbstractTable
{
    protected const COL_ID = 'id_supplier';
    protected const COL_NAME = 'name';
    protected const COL_EMAIL = 'email';
    protected const COL_PHONE = 'phone';
    protected const COL_IS_ACTIVE = 'is_active';
    protected const COL_ACTIONS = 'actions';

    /**
     * @var \Orm\Zed\Supplier\Persistence\SpySupplierQuery
     */
    protected SpySupplierQuery $supplierQuery;

    /**
     * @param \Orm\Zed\Supplier\Persistence\SpySupplierQuery $supplierQuery
     */
    public function __construct(SpySupplierQuery $supplierQuery)
    {
        $this->supplierQuery = $supplierQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID => 'ID',
            static::COL_NAME => 'Name',
            static::COL_EMAIL => 'Email',
            static::COL_PHONE => 'Phone',
            static::COL_IS_ACTIVE => 'Active',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            static::COL_NAME,
            static::COL_EMAIL,
        ]);

        $config->setSortable([
            static::COL_ID,
            static::COL_NAME,
            static::COL_EMAIL,
        ]);

        $config->setDefaultSortField(static::COL_ID, TableConfiguration::SORT_DESC);

        $config->addRawColumn(static::COL_ACTIONS);
        $config->addRawColumn(static::COL_IS_ACTIVE);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->supplierQuery, $config);
        $results = [];

        foreach ($queryResults as $supplierEntity) {
            $results[] = [
                static::COL_ID => $supplierEntity[SpySupplierTableMap::COL_ID_SUPPLIER],
                static::COL_NAME => $supplierEntity[SpySupplierTableMap::COL_NAME],
                static::COL_EMAIL => $supplierEntity[SpySupplierTableMap::COL_EMAIL],
                static::COL_PHONE => $supplierEntity[SpySupplierTableMap::COL_PHONE],
                static::COL_IS_ACTIVE => $this->generateActiveLabel($supplierEntity[SpySupplierTableMap::COL_IS_ACTIVE]),
                static::COL_ACTIONS => $this->generateActionButtons($supplierEntity[SpySupplierTableMap::COL_ID_SUPPLIER]),
            ];
        }

        return $results;
    }

    /**
     * @param bool $isActive
     *
     * @return string
     */
    protected function generateActiveLabel(bool $isActive): string
    {
        $labelClass = $isActive ? 'label-info' : 'label-danger';
        $labelText = $isActive ? 'Active' : 'Inactive';

        return sprintf('<span class="label %s">%s</span>', $labelClass, $labelText);
    }

    /**
     * @param int $idSupplier
     *
     * @return string
     */
    protected function generateActionButtons(int $idSupplier): string
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            sprintf('/supplier/edit?id-supplier=%d', $idSupplier),
            'Edit'
        );

        $buttons[] = $this->generateRemoveButton(
            sprintf('/supplier/delete?id-supplier=%d', $idSupplier),
            'Delete'
        );

        return implode(' ', $buttons);
    }
}
```

**Template:** `src/Pyz/Zed/Supplier/Presentation/Index/index.twig`

```twig
{% extends '@Gui/Layout/layout.twig' %}

{% block content %}
    {% embed '@Gui/Partials/widget.twig' with {widget_title: 'Suppliers'} %}
        {% block widget_content %}
            <div class="spy-page-actions">
                <a href="{{ url('/supplier/create') }}" class="btn btn-primary">
                    Create Supplier
                </a>
            </div>

            {{ supplierTable | raw }}
        {% endblock %}
    {% endembed %}
{% endblock %}
```

#### 2. Create Form

**Form Type:** `src/Pyz/Zed/Supplier/Communication/Form/SupplierForm.php`

```php
<?php

namespace Pyz\Zed\Supplier\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SupplierForm extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_EMAIL = 'email';
    public const FIELD_PHONE = 'phone';
    public const FIELD_IS_ACTIVE = 'is_active';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder)
            ->addEmailField($builder)
            ->addPhoneField($builder)
            ->addIsActiveField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_EMAIL, EmailType::class, [
            'label' => 'Email',
            'required' => false,
            'constraints' => [
                new Email(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPhoneField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_PHONE, TextType::class, [
            'label' => 'Phone',
            'required' => false,
            'constraints' => [
                new Length(['max' => 50]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_IS_ACTIVE, CheckboxType::class, [
            'label' => 'Active',
            'required' => false,
        ]);

        return $this;
    }
}
```

**Create Controller Action:**

```php
/**
 * @param \Symfony\Component\HttpFoundation\Request $request
 *
 * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
 */
public function createAction(Request $request)
{
    $supplierForm = $this->getFactory()->createSupplierForm();
    $supplierForm->handleRequest($request);

    if ($supplierForm->isSubmitted() && $supplierForm->isValid()) {
        $supplierTransfer = new SupplierTransfer();
        $supplierTransfer->fromArray($supplierForm->getData(), true);

        $this->getFacade()->createSupplier($supplierTransfer);

        $this->addSuccessMessage('Supplier created successfully.');

        return $this->redirectResponse('/supplier');
    }

    return $this->viewResponse([
        'form' => $supplierForm->createView(),
    ]);
}
```

---

## Chapter 7: Data Import

**Branch Pattern:** `ilt/202512.0/intermediate/data-import/{skeleton|complete}`  
**Time:** 2 hours  
**Difficulty:** ⭐⭐⭐☆☆

### What You'll Learn
- CSV data import
- Data import configuration
- Custom import steps
- Batch processing

### Data Import Flow

```
CSV File
   │
   ▼
DataImportConfig
   │
   ▼
DataImportStep (Pipeline)
   ├─> Read CSV Row
   ├─> Transform Data
   ├─> Validate
   └─> Write to Database
```

### Step-by-Step: Supplier Import

#### 1. CSV Structure

**File:** `data/import/common/common/supplier.csv`

```csv
name,email,phone,is_active
"ACME Corporation","contact@acme.com","+1-555-0100","1"
"Global Supplies","info@globalsupplies.com","+1-555-0200","1"
"Tech Distributors","sales@techdist.com","+1-555-0300","1"
```

#### 2. Import Configuration

**File:** `src/Pyz/Zed/DataImport/DataImportConfig.php`

```php
<?php

namespace Pyz\Zed\DataImport;

use Spryker\Zed\DataImport\DataImportConfig as SprykerDataImportConfig;

class DataImportConfig extends SprykerDataImportConfig
{
    public const IMPORT_TYPE_SUPPLIER = 'supplier';

    /**
     * @return string
     */
    public function getSupplierDataImportFilePath(): string
    {
        return $this->getDataImportRootPath() . 'common/common/supplier.csv';
    }
}
```

#### 3. Import Step

**File:** `src/Pyz/Zed/DataImport/Business/Model/Supplier/SupplierWriterStep.php`

```php
<?php

namespace Pyz\Zed\DataImport\Business\Model\Supplier;

use Orm\Zed\Supplier\Persistence\SpySupplierQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class SupplierWriterStep implements DataImportStepInterface
{
    public const KEY_NAME = 'name';
    public const KEY_EMAIL = 'email';
    public const KEY_PHONE = 'phone';
    public const KEY_IS_ACTIVE = 'is_active';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $supplierEntity = SpySupplierQuery::create()
            ->filterByName($dataSet[static::KEY_NAME])
            ->findOneOrCreate();

        $supplierEntity->setEmail($dataSet[static::KEY_EMAIL]);
        $supplierEntity->setPhone($dataSet[static::KEY_PHONE]);
        $supplierEntity->setIsActive((bool)$dataSet[static::KEY_IS_ACTIVE]);

        $supplierEntity->save();
    }
}
```

#### 4. Register Import

**File:** `src/Pyz/Zed/DataImport/Business/DataImportBusinessFactory.php`

```php
/**
 * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
 */
public function createSupplierImporter(): DataImporterInterface
{
    $dataImporter = $this->getCsvDataImporterFromConfig(
        $this->getConfig()->buildImporterConfiguration(
            $this->getConfig()->getSupplierDataImportFilePath(),
            DataImportConfig::IMPORT_TYPE_SUPPLIER
        )
    );

    $dataImporter->addDataImportStep(new SupplierWriterStep());

    return $dataImporter;
}
```

#### 5. Run Import

```bash
console data:import supplier
```

### Advanced: Multi-Step Import Pipeline

```php
$dataImporter
    ->addDataImportStep(new ValidationStep())
    ->addDataImportStep(new TransformationStep())
    ->addDataImportStep(new EnrichmentStep())
    ->addDataImportStep(new SupplierWriterStep());
```

---

## Chapter 8: Publish & Synchronize

**Branch Pattern:** `ilt/202512.0/intermediate/publish-synchronize/{skeleton|complete}`  
**Time:** 3 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### What You'll Learn
- Event-driven architecture
- Publisher plugins
- Storage synchronization
- Redis/Elasticsearch integration

### Publish & Sync Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Zed (Backend)                        │
├─────────────────────────────────────────────────────────┤
│  Entity Change (Create/Update/Delete)                   │
│           │                                             │
│           ▼                                             │
│  Event Trigger (Propel Behavior)                       │
│           │                                             │
│           ▼                                             │
│  Event Queue (RabbitMQ)                                │
│           │                                             │
│           ▼                                             │
│  Publisher Plugin                                       │
│           │                                             │
│           ▼                                             │
│  Storage Table (spy_supplier_search)                   │
└───────────┬───────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────┐
│             Storage (Redis/Elasticsearch)               │
├─────────────────────────────────────────────────────────┤
│  Queue Worker Syncs Data                                │
│           │                                             │
│           ▼                                             │
│  Elasticsearch Document / Redis Key                     │
└─────────────────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────┐
│              Client/Yves (Frontend)                     │
├─────────────────────────────────────────────────────────┤
│  Read from Redis/Elasticsearch                          │
└─────────────────────────────────────────────────────────┘
```

### Step-by-Step: Supplier Search Publish & Sync

#### 1. Storage Schema

**File:** `src/Pyz/Zed/SupplierSearch/Persistence/Propel/Schema/pyz_supplier_search.schema.xml`

```xml
<?xml version="1.0"?>
<database xmlns="spryker:schema-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    name="zed"
    xsi:schemaLocation="spryker:schema-01 https://static.spryker.com/schema-01.xsd"
    namespace="Orm\Zed\SupplierSearch\Persistence"
    package="src.Orm.Zed.SupplierSearch.Persistence">

    <table name="spy_supplier_search">
        <column name="id_supplier_search" type="INTEGER" autoIncrement="true" primaryKey="true"/>
        <column name="fk_supplier" type="INTEGER" required="true"/>
        <column name="data" type="LONGVARCHAR" required="false"/>
        <column name="key" type="VARCHAR" size="255" required="true"/>

        <index name="spy_supplier_search-key">
            <index-column name="key"/>
        </index>

        <behavior name="synchronization">
            <parameter name="resource" value="supplier"/>
            <parameter name="store" required="false"/>
            <parameter name="locale" required="false"/>
            <parameter name="key_suffix_column" value="fk_supplier"/>
            <parameter name="queue_group" value="sync.search.supplier"/>
        </behavior>

        <behavior name="timestampable"/>

        <id-method-parameter value="spy_supplier_search_pk_seq"/>
    </table>

</database>
```

#### 2. Publisher Plugin

**File:** `src/Pyz/Zed/SupplierSearch/Communication/Plugin/Publisher/SupplierPublisherTriggerPlugin.php`

```php
<?php

namespace Pyz\Zed\SupplierSearch\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\SpySupplierQuery;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Pyz\Zed\SupplierSearch\Business\SupplierSearchFacadeInterface getFacade()
 * @method \Pyz\Zed\SupplierSearch\Communication\SupplierSearchCommunicationFactory getFactory()
 * @method \Pyz\Zed\SupplierSearch\SupplierSearchConfig getConfig()
 */
class SupplierPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\SupplierTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $supplierEntities = SpySupplierQuery::create()
            ->offset($offset)
            ->limit($limit)
            ->find();

        $supplierTransfers = [];
        foreach ($supplierEntities as $supplierEntity) {
            $supplierTransfer = new SupplierTransfer();
            $supplierTransfer->fromArray($supplierEntity->toArray(), true);
            $supplierTransfers[] = $supplierTransfer;
        }

        return $supplierTransfers;
    }

    /**
     * @return string
     */
    public function getResourceName(): string
    {
        return $this->getConfig()->getSupplierResourceName();
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->getConfig()->getSupplierPublishEventName();
    }

    /**
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return 'id_supplier';
    }
}
```

#### 3. Event Subscriber

**File:** `src/Pyz/Zed/SupplierSearch/Communication/Plugin/Publisher/SupplierSearchPublisherPlugin.php`

```php
<?php

namespace Pyz\Zed\SupplierSearch\Communication\Plugin\Publisher;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Pyz\Zed\SupplierSearch\Business\SupplierSearchFacadeInterface getFacade()
 * @method \Pyz\Zed\SupplierSearch\Communication\SupplierSearchCommunicationFactory getFactory()
 * @method \Pyz\Zed\SupplierSearch\SupplierSearchConfig getConfig()
 */
class SupplierSearchPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $supplierIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventEntityTransfers);

        $this->getFacade()->publishSuppliers($supplierIds);
    }

    /**
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            $this->getConfig()->getSupplierPublishEventName(),
            $this->getConfig()->getSupplierCreateEventName(),
            $this->getConfig()->getSupplierUpdateEventName(),
        ];
    }
}
```

#### 4. Search Writer

**File:** `src/Pyz/Zed/SupplierSearch/Business/Writer/SupplierSearchWriter.php`

```php
<?php

namespace Pyz\Zed\SupplierSearch\Business\Writer;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\SupplierSearch\Persistence\SpySupplierSearch;
use Orm\Zed\SupplierSearch\Persistence\SpySupplierSearchQuery;
use Pyz\Zed\SupplierSearch\SupplierSearchConfig;

class SupplierSearchWriter implements SupplierSearchWriterInterface
{
    /**
     * @var \Pyz\Zed\SupplierSearch\SupplierSearchConfig
     */
    protected SupplierSearchConfig $config;

    /**
     * @param \Pyz\Zed\SupplierSearch\SupplierSearchConfig $config
     */
    public function __construct(SupplierSearchConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array<int> $supplierIds
     *
     * @return void
     */
    public function publishSuppliers(array $supplierIds): void
    {
        foreach ($supplierIds as $idSupplier) {
            $this->publishSupplier($idSupplier);
        }
    }

    /**
     * @param int $idSupplier
     *
     * @return void
     */
    protected function publishSupplier(int $idSupplier): void
    {
        $supplierSearchEntity = SpySupplierSearchQuery::create()
            ->filterByFkSupplier($idSupplier)
            ->findOneOrCreate();

        $searchData = $this->buildSearchData($idSupplier);

        $supplierSearchEntity->setData($searchData);
        $supplierSearchEntity->setKey($this->buildSearchKey($idSupplier));
        $supplierSearchEntity->save();
    }

    /**
     * @param int $idSupplier
     *
     * @return string
     */
    protected function buildSearchData(int $idSupplier): string
    {
        $supplierData = $this->getSupplierData($idSupplier);

        $searchData = [
            'type' => $this->config->getSupplierResourceType(),
            'id_supplier' => $idSupplier,
            'name' => $supplierData->getName(),
            'email' => $supplierData->getEmail(),
            'search-result-data' => [
                'idSupplier' => $idSupplier,
                'name' => $supplierData->getName(),
                'email' => $supplierData->getEmail(),
                'isActive' => $supplierData->getIsActive(),
            ],
        ];

        return json_encode($searchData);
    }

    /**
     * @param int $idSupplier
     *
     * @return string
     */
    protected function buildSearchKey(int $idSupplier): string
    {
        return sprintf('%s:%d', $this->config->getSupplierResourceType(), $idSupplier);
    }
}
```

#### 5. Trigger Publishing

```bash
# Trigger publish events for all suppliers
console publish:trigger-events -r supplier

# Start queue worker to sync to Elasticsearch
console queue:worker:start
```

---

## Chapter 9: Elasticsearch Integration

**Branch Pattern:** `ilt/202512.0/intermediate/search/{skeleton|complete}`  
**Time:** 3 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### What You'll Learn
- Elasticsearch client setup
- Search query plugins
- Result formatters
- Faceted search

### Search Architecture

```
┌─────────────────────────────────────────────────────────┐
│                   Client Layer                          │
├─────────────────────────────────────────────────────────┤
│  SearchClient                                           │
│       │                                                 │
│       ├─> Query Plugin (Build ES Query)                │
│       │                                                 │
│       ├─> Query Expander Plugins (Add filters)         │
│       │                                                 │
│       ▼                                                 │
│  Elasticsearch Query                                    │
└───────┬─────────────────────────────────────────────────┘
        │
        ▼
┌─────────────────────────────────────────────────────────┐
│               Elasticsearch                             │
├─────────────────────────────────────────────────────────┤
│  Search & Return Results                                │
└───────┬─────────────────────────────────────────────────┘
        │
        ▼
┌─────────────────────────────────────────────────────────┐
│                  Client Layer                           │
├─────────────────────────────────────────────────────────┤
│  Result Formatter Plugins                               │
│       │                                                 │
│       └─> Format to Transfers                          │
└─────────────────────────────────────────────────────────┘
```

### Step-by-Step: Supplier Search Client

#### 1. Search Query Plugin

**File:** `src/Pyz/Client/SupplierSearch/Plugin/Elasticsearch/Query/SupplierSearchQueryPlugin.php`

```php
<?php

namespace Pyz\Client\SupplierSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Generated\Shared\Search\SupplierIndexMap;
use Pyz\Client\SupplierSearch\SupplierSearchConfig;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Pyz\Client\SupplierSearch\SupplierSearchFactory getFactory()
 */
class SupplierSearchQueryPlugin extends AbstractPlugin implements QueryInterface
{
    protected const SOURCE_IDENTIFIER = SupplierSearchConfig::SUPPLIER_SOURCE_IDENTIFIER;
    protected const RESOURCE_TYPE = SupplierSearchConfig::SUPPLIER_RESOURCE_TYPE;

    /**
     * @var string
     */
    protected string $searchString = '';

    /**
     * @param string $searchString
     */
    public function __construct(string $searchString = '')
    {
        $this->searchString = $searchString;
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        $query = $this->createSearchQuery();

        return $query;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery(): Query
    {
        $boolQuery = new BoolQuery();

        $this->addTypeFilter($boolQuery);
        $this->addFullTextSearch($boolQuery);

        $query = new Query($boolQuery);
        $query->setSource([SupplierIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return void
     */
    protected function addTypeFilter(BoolQuery $boolQuery): void
    {
        $typeMatch = new Match();
        $typeMatch->setField(SupplierIndexMap::TYPE, static::RESOURCE_TYPE);

        $boolQuery->addMust($typeMatch);
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return void
     */
    protected function addFullTextSearch(BoolQuery $boolQuery): void
    {
        if ($this->searchString === '') {
            $boolQuery->addMust(new MatchAll());
            return;
        }

        $matchQuery = new Match();
        $matchQuery->setFieldQuery(SupplierIndexMap::NAME, $this->searchString);
        $matchQuery->setFieldFuzziness(SupplierIndexMap::NAME, 'AUTO');

        $boolQuery->addMust($matchQuery);
    }
}
```

#### 2. Result Formatter Plugin

**File:** `src/Pyz/Client/SupplierSearch/Plugin/Elasticsearch/ResultFormatter/SupplierSearchResultFormatterPlugin.php`

```php
<?php

namespace Pyz\Client\SupplierSearch\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\SupplierIndexMap;
use Generated\Shared\Transfer\SupplierSearchResultTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

class SupplierSearchResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    protected const NAME = 'suppliers';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\SupplierSearchResultTransfer
     */
    public function formatResult(ResultSet $searchResult, array $requestParameters = []): SupplierSearchResultTransfer
    {
        $supplierSearchResultTransfer = new SupplierSearchResultTransfer();
        $supplierSearchResultTransfer->setTotalCount($searchResult->getTotalHits());

        foreach ($searchResult->getResults() as $result) {
            $supplierTransfer = $this->mapResultToSupplierTransfer($result->getSource());
            $supplierSearchResultTransfer->addSupplier($supplierTransfer);
        }

        return $supplierSearchResultTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    protected function mapResultToSupplierTransfer(array $data): SupplierTransfer
    {
        $searchResultData = $data[SupplierIndexMap::SEARCH_RESULT_DATA] ?? [];

        $supplierTransfer = new SupplierTransfer();
        $supplierTransfer->fromArray($searchResultData, true);

        return $supplierTransfer;
    }
}
```

#### 3. Search Client Facade

**File:** `src/Pyz/Client/SupplierSearch/SupplierSearchClient.php`

```php
<?php

namespace Pyz\Client\SupplierSearch;

use Generated\Shared\Transfer\SupplierSearchResultTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\SupplierSearch\SupplierSearchFactory getFactory()
 */
class SupplierSearchClient extends AbstractClient implements SupplierSearchClientInterface
{
    /**
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\SupplierSearchResultTransfer
     */
    public function searchSuppliers(string $searchString, array $requestParameters = []): SupplierSearchResultTransfer
    {
        $searchQuery = $this->getFactory()->createSupplierSearchQuery($searchString);

        $resultFormatters = $this->getFactory()->getSupplierSearchResultFormatters();

        $searchResults = $this->getFactory()
            ->getSearchClient()
            ->search($searchQuery, [], $resultFormatters);

        return $searchResults['suppliers'];
    }
}
```

#### 4. Usage Example

```php
// In controller or business layer
$searchResult = $this->getClient()->searchSuppliers('acme', [
    'page' => 1,
    'itemsPerPage' => 10,
]);

foreach ($searchResult->getSuppliers() as $supplier) {
    echo $supplier->getName() . PHP_EOL;
}

echo 'Total: ' . $searchResult->getTotalCount();
```

---

## Chapter 10: API Platform (Glue Storefront)

**Branch Pattern:** `ilt/202512.0/intermediate/glue-storefront/{skeleton|complete}`  
**Time:** 3 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### What You'll Learn
- REST API development with API Platform
- Resource providers
- Request/response mapping
- API documentation

### API Platform Architecture

```
HTTP Request
     │
     ▼
┌─────────────────────────────────────┐
│     API Platform Route              │
│  GET /suppliers                     │
│  GET /suppliers/{id}                │
│  POST /suppliers                    │
└──────────┬──────────────────────────┘
           │
           ▼
┌─────────────────────────────────────┐
│     Resource Provider               │
│  - getCollection()                  │
│  - get($id)                         │
│  - create($data)                    │
└──────────┬──────────────────────────┘
           │
           ▼
┌─────────────────────────────────────┐
│     Client/Zed Communication        │
│  (Search/Facade calls)              │
└──────────┬──────────────────────────┘
           │
           ▼
┌─────────────────────────────────────┐
│     Mapper                          │
│  Transfer → API Resource            │
└──────────┬──────────────────────────┘
           │
           ▼
JSON Response
```

### Step-by-Step: Suppliers API

#### 1. Resource Provider

**File:** `src/Pyz/Glue/SuppliersApi/Provider/SupplierResourceProvider.php`

```php
<?php

namespace Pyz\Glue\SuppliersApi\Provider;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\SuppliersApiAttributesTransfer;
use Pyz\Glue\SuppliersApi\Processor\Mapper\SupplierMapperInterface;
use Spryker\Glue\Kernel\Backend\AbstractResourceProvider;

/**
 * @method \Pyz\Glue\SuppliersApi\SuppliersApiFactory getFactory()
 */
class SupplierResourceProvider extends AbstractResourceProvider
{
    /**
     * @return string
     */
    public function getResourceClass(): string
    {
        return SuppliersApiAttributesTransfer::class;
    }

    /**
     * @return array
     */
    public function getOperations(): array
    {
        return [
            GetCollection::class => [
                'method' => 'GET',
                'path' => '/suppliers',
                'provider' => [$this, 'getCollection'],
            ],
            Get::class => [
                'method' => 'GET',
                'path' => '/suppliers/{id}',
                'provider' => [$this, 'get'],
            ],
            Post::class => [
                'method' => 'POST',
                'path' => '/suppliers',
                'provider' => [$this, 'create'],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SuppliersApiAttributesTransfer[]
     */
    public function getCollection(GlueRequestTransfer $glueRequestTransfer): array
    {
        $searchString = $glueRequestTransfer->getQueryString()['q'] ?? '';

        $searchResult = $this->getFactory()
            ->getSupplierSearchClient()
            ->searchSuppliers($searchString);

        return $this->getFactory()
            ->createSupplierMapper()
            ->mapSupplierSearchResultToApiAttributes($searchResult);
    }

    /**
     * @param string $id
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SuppliersApiAttributesTransfer|null
     */
    public function get(string $id, GlueRequestTransfer $glueRequestTransfer): ?SuppliersApiAttributesTransfer
    {
        $supplierTransfer = $this->getFactory()
            ->getSupplierClient()
            ->findSupplierById((int)$id);

        if ($supplierTransfer === null) {
            return null;
        }

        return $this->getFactory()
            ->createSupplierMapper()
            ->mapSupplierTransferToApiAttributes($supplierTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SuppliersApiAttributesTransfer $apiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SuppliersApiAttributesTransfer
     */
    public function create(
        SuppliersApiAttributesTransfer $apiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): SuppliersApiAttributesTransfer {
        $supplierTransfer = $this->getFactory()
            ->createSupplierMapper()
            ->mapApiAttributesToSupplierTransfer($apiAttributesTransfer);

        $supplierTransfer = $this->getFactory()
            ->getSupplierClient()
            ->createSupplier($supplierTransfer);

        return $this->getFactory()
            ->createSupplierMapper()
            ->mapSupplierTransferToApiAttributes($supplierTransfer);
    }
}
```

#### 2. API Attributes Transfer

**File:** `src/Pyz/Shared/SuppliersApi/Transfer/suppliers_api.transfer.xml`

```xml
<?xml version="1.0"?>
<transfers xmlns="spryker:transfer-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">

    <transfer name="SuppliersApiAttributes">
        <property name="id" type="int"/>
        <property name="name" type="string"/>
        <property name="email" type="string"/>
        <property name="phone" type="string"/>
        <property name="isActive" type="bool"/>
    </transfer>

</transfers>
```

#### 3. Mapper

**File:** `src/Pyz/Glue/SuppliersApi/Processor/Mapper/SupplierMapper.php`

```php
<?php

namespace Pyz\Glue\SuppliersApi\Processor\Mapper;

use Generated\Shared\Transfer\SupplierSearchResultTransfer;
use Generated\Shared\Transfer\SuppliersApiAttributesTransfer;
use Generated\Shared\Transfer\SupplierTransfer;

class SupplierMapper implements SupplierMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SuppliersApiAttributesTransfer
     */
    public function mapSupplierTransferToApiAttributes(
        SupplierTransfer $supplierTransfer
    ): SuppliersApiAttributesTransfer {
        $apiAttributes = new SuppliersApiAttributesTransfer();

        // Use toArray with camelCase conversion
        $apiAttributes->fromArray($supplierTransfer->toArray(false, true), true);

        return $apiAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\SuppliersApiAttributesTransfer $apiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function mapApiAttributesToSupplierTransfer(
        SuppliersApiAttributesTransfer $apiAttributesTransfer
    ): SupplierTransfer {
        $supplierTransfer = new SupplierTransfer();
        $supplierTransfer->fromArray($apiAttributesTransfer->toArray(), true);

        return $supplierTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierSearchResultTransfer $searchResult
     *
     * @return array<\Generated\Shared\Transfer\SuppliersApiAttributesTransfer>
     */
    public function mapSupplierSearchResultToApiAttributes(
        SupplierSearchResultTransfer $searchResult
    ): array {
        $apiAttributes = [];

        foreach ($searchResult->getSuppliers() as $supplierTransfer) {
            $apiAttributes[] = $this->mapSupplierTransferToApiAttributes($supplierTransfer);
        }

        return $apiAttributes;
    }
}
```

#### 4. Register in Application

**File:** `src/Pyz/Glue/Backend/ApplicationServices.php`

```php
use Pyz\Glue\SuppliersApi\Provider\SupplierResourceProvider;

/**
 * @return array
 */
protected function getResourceProviders(): array
{
    return [
        SupplierResourceProvider::class,
        // ... other providers
    ];
}
```

#### 5. API Endpoints

**GET Collection:**
```bash
curl -X GET "http://glue.eu.spryker.local/suppliers?q=acme"
```

**Response:**
```json
{
  "data": [
    {
      "type": "suppliers",
      "id": "1",
      "attributes": {
        "name": "ACME Corporation",
        "email": "contact@acme.com",
        "phone": "+1-555-0100",
        "isActive": true
      }
    }
  ]
}
```

**GET Single:**
```bash
curl -X GET "http://glue.eu.spryker.local/suppliers/1"
```

**POST Create:**
```bash
curl -X POST "http://glue.eu.spryker.local/suppliers" \
  -H "Content-Type: application/json" \
  -d '{
    "data": {
      "type": "suppliers",
      "attributes": {
        "name": "New Supplier",
        "email": "new@supplier.com",
        "phone": "+1-555-9999",
        "isActive": true
      }
    }
  }'
```

---

## Chapter 11: Order Management System (OMS)

**Branch Pattern:** `ilt/202512.0/intermediate/oms/{skeleton|complete}`  
**Time:** 4 hours  
**Difficulty:** ⭐⭐⭐⭐⭐

### What You'll Learn
- OMS state machines
- Command and Condition plugins
- State transitions and events
- Order workflow automation

### OMS Architecture

```
┌─────────────────────────────────────────────────────────┐
│              OMS State Machine (XML)                    │
├─────────────────────────────────────────────────────────┤
│  States → Transitions → Events                          │
│           │                                             │
│           ├─> Commands (Actions)                        │
│           └─> Conditions (Business Logic Checks)        │
└───────────┬─────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────┐
│              Order Item State                           │
├─────────────────────────────────────────────────────────┤
│  new → payment pending → paid → shipped → delivered     │
└─────────────────────────────────────────────────────────┘
```

### Demo01 State Machine Flow

```
┌──────┐         ┌─────────────────┐         ┌──────────────────┐
│ new  │────────>│payment pending  │────────>│payment authorized│
└──────┘         └─────────────────┘         └──────────────────┘
   │                                                    │
   │ IsAuthorized=false                                │
   ▼                                                    ▼
┌────────┐                                       ┌─────────┐
│invalid │                                       │  paid   │
└────────┘                                       └─────────┘
                                                      │
                                                      ▼
                                                 ┌─────────┐
                                                 │ closed  │
                                                 └─────────┘
```

### Step-by-Step: Demo01 OMS

#### 1. State Machine Definition

**File:** `config/Zed/oms/Demo01.xml`

```xml
<?xml version="1.0"?>
<statemachine
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns="spryker:oms-01"
    xsi:schemaLocation="spryker:oms-01 http://static.spryker.com/oms-01.xsd">

    <process name="Demo01" main="true">
        <states>
            <state name="new" reserved="true" display="oms.state.new"/>
            <state name="payment pending" reserved="true" display="oms.state.payment-pending"/>
            <state name="invalid" display="oms.state.invalid"/>
            <state name="payment authorized" reserved="true" display="oms.state.payment-authorized"/>
            <state name="paid" reserved="true" display="oms.state.paid"/>
            <state name="closed" display="oms.state.closed"/>
        </states>

        <transitions>
            <transition happy="true" condition="Demo/IsAuthorized">
                <source>new</source>
                <target>payment pending</target>
                <event>authorize</event>
            </transition>

            <transition>
                <source>new</source>
                <target>invalid</target>
                <event>authorize</event>
            </transition>

            <transition happy="true">
                <source>payment pending</source>
                <target>payment authorized</target>
                <event>pay</event>
            </transition>

            <transition happy="true">
                <source>payment authorized</source>
                <target>paid</target>
            </transition>

            <transition happy="true">
                <source>paid</source>
                <target>closed</target>
            </transition>
        </transitions>

        <events>
            <event name="authorize" onEnter="true" manual="true" command="Demo/Pay"/>
            <event name="pay" onEnter="true" manual="true"/>
        </events>
    </process>
</statemachine>
```

#### 2. Command Plugin

**File:** `src/SprykerAcademy/Zed/Oms/Communication/Plugin/Oms/Command/PayCommandPlugin.php`

```php
<?php

namespace SprykerAcademy\Zed\Oms\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

class PayCommandPlugin extends AbstractCommand implements CommandByOrderInterface
{
    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array<mixed>
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): array
    {
        // Demo implementation - in real scenario, integrate with payment gateway
        // Example: Call payment API, log transaction, etc.

        return [];
    }
}
```

#### 3. Condition Plugin

**File:** `src/SprykerAcademy/Zed/Oms/Communication/Plugin/Oms/Condition/IsAuthorizedConditionPlugin.php`

```php
<?php

namespace SprykerAcademy\Zed\Oms\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\AbstractCondition;

class IsAuthorizedConditionPlugin extends AbstractCondition
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        // Demo implementation - always returns true
        // In real scenario, check payment authorization status

        return true;
    }
}
```

#### 4. Register Plugins

**File:** `src/SprykerAcademy/Zed/Oms/OmsDependencyProvider.php`

```php
<?php

namespace SprykerAcademy\Zed\Oms;

use Pyz\Zed\Oms\OmsDependencyProvider as PyzOmsDependencyProvider;
use SprykerAcademy\Zed\Oms\Communication\Plugin\Oms\Command\PayCommandPlugin;
use SprykerAcademy\Zed\Oms\Communication\Plugin\Oms\Condition\IsAuthorizedConditionPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;

class OmsDependencyProvider extends PyzOmsDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendCommandPlugins(Container $container): Container
    {
        $container = parent::extendCommandPlugins($container);

        $container->extend(self::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            $commandCollection->add(new PayCommandPlugin(), 'Demo/Pay');

            return $commandCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendConditionPlugins(Container $container): Container
    {
        $container = parent::extendConditionPlugins($container);

        $container->extend(self::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection->add(new IsAuthorizedConditionPlugin(), 'Demo/IsAuthorized');

            return $conditionCollection;
        });

        return $container;
    }
}
```

#### 5. Configure OMS Process

**File:** `config/Shared/config_default.php`

```php
use Spryker\Shared\Oms\OmsConstants;

$config[OmsConstants::PROCESS_LOCATION] = [
    APPLICATION_ROOT_DIR . '/config/Zed/oms',
];

$config[OmsConstants::ACTIVE_PROCESSES] = [
    'Demo01',
];
```

#### 6. Test OMS

**Create Test Order:**
```bash
# In Back Office, create an order with Demo01 process
# Or use API to create order
```

**Trigger State Machine:**
```bash
# Check OMS state
console oms:check-condition

# Check timeouts
console oms:check-timeout
```

**View in Back Office:**
- Navigate to Sales → Orders
- View order state diagram
- Manually trigger events (authorize, pay)

### OMS Concepts

**States:**
- `reserved="true"` - Reserves inventory
- `display` - Translation key for display name

**Transitions:**
- `happy="true"` - Main business flow
- `condition` - Plugin that must return true
- `event` - Trigger for transition

**Events:**
- `manual="true"` - Can be triggered manually
- `onEnter="true"` - Triggers automatically
- `timeout` - Scheduled execution (e.g., "1 day")
- `command` - Execute business logic

### Best Practices

✅ **DO:**
- Model real business processes
- Use descriptive state names
- Mark happy path with `happy="true"`
- Keep commands idempotent
- Use conditions for business rules

❌ **DON'T:**
- Create circular transitions
- Skip error states
- Put complex logic in plugins
- Forget to register plugins

---

**End of Spryker Backend Development Training Manual v202512.0 - Complete Edition**

---

For questions or support, refer to:
- **Spryker Documentation**: https://docs.spryker.com
- **Spryker Academy**: https://academy.spryker.com
- **Community Forum**: https://discuss.spryker.com

---

## Appendix A: Quick Reference

### Common Commands

```bash
# Transfer Objects
console transfer:generate

# Database
console propel:install
console propel:migrate

# Search
console search:setup:sources
console queue:worker:start

# Data Import
console data:import

# Cache
console cache:empty-all
```

### Module Directory Structure

```
src/Pyz/Zed/{Module}/
├── Business/
│   ├── {Module}BusinessFactory.php
│   ├── {Module}Facade.php
│   └── Model/
├── Communication/
│   ├── Controller/
│   ├── Form/
│   └── Table/
├── Persistence/
│   ├── {Module}EntityManager.php
│   ├── {Module}Repository.php
│   └── Propel/Schema/
└── Presentation/
    └── {Controller}/
```

---

## Appendix B: Glossary

- **Transfer Object**: Type-safe data container
- **Facade**: Public API of module's Business layer
- **Repository**: Read operations from database
- **Entity Manager**: Write operations to database
- **Propel**: ORM used by Spryker
- **OMS**: Order Management System

---

**End of Spryker Backend Development Training Manual v202512.0**
