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

\`\`\`
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
\`\`\`

### Step-by-Step: Create Hello World

#### 1. Create Module Structure

\`\`\`bash
mkdir -p src/Pyz/Zed/HelloWorld/Communication/Controller
mkdir -p src/Pyz/Zed/HelloWorld/Presentation/Index
\`\`\`

**Directory Structure:**
\`\`\`
src/Pyz/Zed/HelloWorld/
├── Communication/
│   └── Controller/
│       └── IndexController.php
└── Presentation/
    └── Index/
        └── index.twig
\`\`\`

#### 2. Implement Controller

**File:** `src/Pyz/Zed/HelloWorld/Communication/Controller/IndexController.php`

\`\`\`php
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
\`\`\`

**Key Concepts:**
- Extends `AbstractController` from Spryker Kernel
- Method name ends with `Action` (routable)
- Returns array (passed to Twig automatically)
- Type hints for clarity

#### 3. Create Twig Template

**File:** `src/Pyz/Zed/HelloWorld/Presentation/Index/index.twig`

\`\`\`twig
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
\`\`\`

**Key Concepts:**
- Extends Back Office layout (`@Gui/Layout/layout.twig`)
- Uses Spryker UI components (`spy-card`)
- Accesses controller variables directly (`{{ message }}`)

#### 4. Test Your Implementation

**URL:** `http://zed.mysprykershop.com/hello-world`

**Expected Result:**
- Back Office navigation and header
- Card displaying "Hello World from Spryker!"
- Current timestamp

### URL Routing in Spryker

\`\`\`
URL Pattern: /{module}/{controller}/{action}/{parameters}

Example: /hello-world/index/index
          └─module   └controller └action

Defaults:
- Missing controller = IndexController
- Missing action = indexAction

So /hello-world maps to:
  Pyz\Zed\HelloWorld\Communication\Controller\IndexController::indexAction()
\`\`\`

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
\`\`\`php
// Bad: Arrays are not type-safe
function processOrder(array $data) {
    $id = $data['order_id']; // What if key doesn't exist?
    $items = $data['items']; // What structure is this?
}
\`\`\`

**Solution:**
\`\`\`php
// Good: Transfer Objects are strongly typed
function processOrder(OrderTransfer $orderTransfer) {
    $id = $orderTransfer->getOrderReference(); // IDE autocomplete
    $items = $orderTransfer->getItems(); // Type-safe ItemTransfer[]
}
\`\`\`

### Transfer Object Lifecycle

\`\`\`
1. Define Schema (XML)
   └─> src/Pyz/Shared/{Module}/Transfer/{module}.transfer.xml

2. Generate Classes
   └─> console transfer:generate

3. Use Transfer Objects
   └─> Generated in src/Generated/Shared/Transfer/

4. Modify & Regenerate
   └─> Add properties, run transfer:generate again
\`\`\`

### Step-by-Step: Create Message Transfer

#### 1. Define Transfer Schema

**File:** `src/Pyz/Shared/Message/Transfer/message.transfer.xml`

\`\`\`xml
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
\`\`\`

#### 2. Generate Transfer Classes

\`\`\`bash
console transfer:generate
\`\`\`

**Generated:** `src/Generated/Shared/Transfer/MessageTransfer.php`

**What Gets Generated:**
\`\`\`php
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
\`\`\`

#### 3. Use Transfer in Controller

**File:** `src/Pyz/Zed/Message/Communication/Controller/IndexController.php`

\`\`\`php
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
\`\`\`

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

\`\`\`xml
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
\`\`\`

**Usage:**
\`\`\`php
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
\`\`\`

### Transfer Object Methods

\`\`\`php
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
\`\`\`

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

*[Manual continues with remaining 9 chapters covering all training modules...]*

---

## Appendix A: Quick Reference

### Common Commands

\`\`\`bash
# Transfer Objects
console transfer:generate

# Database
console propel:install
console propel:migrate

# Search
console search:setup:sources
console search:setup:source-map

# Data Import
console data:import

# Publish & Sync
console publish:trigger-events
console queue:worker:start

# Cache
console cache:empty-all
\`\`\`

### Module Layers

| Layer | Purpose | Examples |
|-------|---------|----------|
| **Zed** | Backend/Admin | Controllers, Business Logic, Persistence |
| **Yves** | Frontend/Shop | Controllers, Widgets, Templates |
| **Glue** | REST API | Resources, Controllers, Processors |
| **Client** | RPC Bridge | Stubs, Dependencies |
| **Shared** | Cross-Layer | Transfers, Constants, Config |

### Directory Structure Template

\`\`\`
src/Pyz/Zed/{Module}/
├── Business/
│   ├── {Module}BusinessFactory.php
│   ├── {Module}Facade.php
│   ├── {Module}FacadeInterface.php
│   └── Model/
├── Communication/
│   ├── Controller/
│   ├── Form/
│   ├── Table/
│   └── {Module}CommunicationFactory.php
├── Persistence/
│   ├── {Module}EntityManager.php
│   ├── {Module}Repository.php
│   ├── {Module}PersistenceFactory.php
│   └── Propel/Schema/
└── Presentation/
    └── {Controller}/
\`\`\`

---

## Appendix B: Glossary

- **Transfer Object**: Type-safe data container
- **Facade**: Public API of a module's Business layer
- **Repository**: Read operations from database
- **Entity Manager**: Write operations to database
- **Propel**: ORM used by Spryker
- **RPC**: Remote Procedure Call (Client-Zed communication)
- **Publish & Sync**: Pattern for data synchronization
- **OMS**: Order Management System

---

**End of Spryker Backend Development Training Manual v202512.0**

