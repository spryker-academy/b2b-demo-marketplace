# Spryker Quick Reference Guide

## Module Structure Template

```
src/SprykerAcademy/Zed/ModuleName/
├── Business/
│   ├── ModuleNameFacade.php
│   ├── ModuleNameFacadeInterface.php
│   ├── ModuleNameBusinessFactory.php
│   └── Model/
│       └── SomeManager.php
├── Communication/
│   ├── ModuleNameCommunicationFactory.php
│   ├── Controller/
│   │   └── SomeController.php
│   ├── Form/
│   ├── Plugin/
│   └── Table/
├── Persistence/
│   ├── ModuleNamePersistenceFactory.php
│   ├── ModuleNameQueryContainer.php
│   ├── ModuleNameQueryContainerInterface.php
│   └── Propel/
│       ├── Schema/
│       │   └── pyz_modulename.schema.xml
│       └── (generated files)
├── Presentation/
│   └── Templates/
│       └── Controller/
│           └── action.twig
└── ModuleNameDependencyProvider.php
```

## Common Commands

```bash
# Transfer generation
vendor/bin/console transfer:generate

# Database migrations
vendor/bin/console propel:install
vendor/bin/console propel:migration:create
vendor/bin/console propel:migration:up
vendor/bin/console propel:migration:down

# Code generation
vendor/bin/console code:generate

# Cache clearing
vendor/bin/console cache:clear

# Testing
vendor/bin/console code:test

# Running tests
vendor/bin/codecept run
vendor/bin/codecept run --group Unit
vendor/bin/codecept run tests/SprykerAcademyTest/Zed/Module/
```

## File Template - Controller

```php
<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\ModuleName\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SomeController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(Request $request): array
    {
        // Get facade
        $data = $this->getFacade()->getSomeData();

        return $this->viewResponse([
            'data' => $data,
        ]);
    }

    /**
     * @return \SprykerAcademy\Zed\ModuleName\Business\ModuleNameFacadeInterface
     */
    protected function getFacade()
    {
        return $this->getLocator()
            ->moduleName()
            ->facade();
    }
}
```

## File Template - Facade

```php
<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\ModuleName\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerAcademy\Zed\ModuleName\Business\ModuleNameBusinessFactory getFactory()
 */
class ModuleNameFacade extends AbstractFacade implements ModuleNameFacadeInterface
{
    /**
     * @return string
     */
    public function getSomeData(): string
    {
        return $this->getFactory()
            ->createSomeManager()
            ->getData();
    }
}
```

## File Template - Manager

```php
<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\ModuleName\Business\Model;

use SprykerAcademy\Zed\ModuleName\Persistence\ModuleNameQueryContainerInterface;

class SomeManager
{
    public function __construct(
        private ModuleNameQueryContainerInterface $queryContainer
    ) {
    }

    public function getData(): string
    {
        return 'Some data';
    }
}
```

## File Template - Query Container

```php
<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\ModuleName\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \SprykerAcademy\Zed\ModuleName\Persistence\ModuleNamePersistenceFactory getFactory()
 */
class ModuleNameQueryContainer extends AbstractQueryContainer
    implements ModuleNameQueryContainerInterface
{
    /**
     * @return \Orm\Zed\ModuleName\Persistence\SomeQuery
     */
    public function querySome()
    {
        return $this->getFactory()
            ->createSomeQuery();
    }
}
```

## File Template - Transfer Definition

```xml
<?xml version="1.0"?>
<transfers xmlns="spryker:transfer-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">

    <transfer name="Something">
        <property name="id" type="int"/>
        <property name="name" type="string"/>
    </transfer>

</transfers>
```

## File Template - Propel Schema

```xml
<?xml version="1.0"?>
<database xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    name="zed"
    xsi:noNamespaceSchemaLocation="http://static.spryker.com/schema-01.xsd"
    namespace="Orm\Zed\ModuleName\Persistence"
    package="src.Orm.Zed.ModuleName.Persistence">

    <table name="pyz_something" idMethod="native" phpName="Something">
        <column name="id_something" type="integer" primaryKey="true" autoIncrement="true"/>
        <column name="name" type="varchar" size="255" required="true"/>
        <column name="created_at" type="timestamp" defaultExpr="CURRENT_TIMESTAMP"/>
    </table>

</database>
```

## File Template - Twig Template

```twig
{% extends '@SprykerGui/Layout/layout.twig' %}

{% block content %}
    <div class="container">
        <h1>Page Title</h1>

        {% if data %}
            {{ data }}
        {% else %}
            <p>No data available</p>
        {% endif %}
    </div>
{% endblock %}
```

## File Template - Unit Test

```php
<?php

namespace SprykerAcademyTest\Zed\ModuleName\Business;

use Codeception\Test\Unit;
use SprykerAcademy\Zed\ModuleName\Business\ModuleNameFacade;

class ModuleNameFacadeTest extends Unit
{
    private ModuleNameFacade $facade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->facade = new ModuleNameFacade();
    }

    public function testGetSomeDataReturnsString(): void
    {
        // Act
        $result = $this->facade->getSomeData();

        // Assert
        $this->assertIsString($result);
    }
}
```

## Naming Conventions

| Element | Convention | Example |
|---------|-----------|---------|
| Classes | PascalCase | `HelloWorldFacade`, `SomeManager` |
| Methods | camelCase | `getData()`, `createSomething()` |
| Properties | $camelCase | `$idSupplier`, `$isActive` |
| Constants | UPPER_SNAKE_CASE | `DEFAULT_TIMEOUT`, `MAX_ITEMS` |
| Tables | pyz_snake_case | `pyz_supplier`, `pyz_message` |
| Columns | snake_case | `id_supplier`, `created_at` |
| Transfer files | snake_case.transfer.xml | `supplier.transfer.xml` |
| Schema files | pyz_snake_case.schema.xml | `pyz_supplier.schema.xml` |
| Routes | kebab-case | `/supplier/supplier/index` |
| URL paths | kebab-case | `/module-name/controller/action` |

## Common Property Types in Transfers

| Type | PHP Type | Usage |
|------|----------|-------|
| `int` | int | Numeric IDs, counts |
| `string` | string | Text, names, emails |
| `bool` | bool | Flags, toggles |
| `float` | float | Prices, percentages |
| `array` | array | Collections, key-value data |
| `Transfer` | TransferObject | Nested object |
| `Transfer[]` | array of objects | Collections of objects |

## Common Column Types in Propel

| Type | Size | Usage |
|------|------|-------|
| `integer` | - | IDs, counts, integers |
| `varchar` | 255 (or custom) | Short text, emails, names |
| `text` | - | Medium text |
| `longvarchar` | - | Long text, descriptions |
| `boolean` | - | True/False values |
| `decimal` | - | Prices with precision |
| `float` | - | Approximate decimal numbers |
| `timestamp` | - | Dates and times |
| `date` | - | Dates only |
| `time` | - | Times only |
| `blob` | - | Binary data |
| `json` | - | JSON data |

## Transfer Object Usage Patterns

```php
// Create new transfer
$transfer = new SomeTransfer();

// Set properties (fluent interface)
$transfer
    ->setName('Value')
    ->setId(1)
    ->setActive(true);

// Get properties
$name = $transfer->getName();

// Create from array
$data = ['name' => 'Value', 'id' => 1];
$transfer = new SomeTransfer($data);

// Convert to array
$array = $transfer->toArray();

// Check if property was set
if ($transfer->modifiedPropertiesHaveValues(['name', 'id'])) {
    // ...
}
```

## Query Object Usage Patterns

```php
// Basic query
$items = SomeQuery::create()
    ->find();

// With filter
$items = SomeQuery::create()
    ->filterByName('Test')
    ->find();

// With ordering
$items = SomeQuery::create()
    ->orderByCreatedAt('desc')
    ->find();

// With limit
$items = SomeQuery::create()
    ->limit(10)
    ->offset(20)
    ->find();

// Single result
$item = SomeQuery::create()
    ->filterByName('Test')
    ->findOne();

// Count
$count = SomeQuery::create()
    ->filterByActive(true)
    ->count();

// With relationship
$items = SomeQuery::create()
    ->useOtherQuery()
        ->filterByStatus('active')
    ->endUse()
    ->find();
```

## Propel Model Usage Patterns

```php
// Create new entity
$entity = new Something();
$entity
    ->setName('Value')
    ->setActive(true)
    ->save();

// Update existing
$entity = SomeQuery::create()
    ->filterById(1)
    ->findOne();

$entity->setName('Updated')->save();

// Delete
$entity->delete();

// Bulk delete
SomeQuery::create()
    ->filterByActive(false)
    ->delete();

// Mass update
SomeQuery::create()
    ->filterByActive(false)
    ->update(['active' => true]);
```

## Facade Usage Patterns

```php
// In Controller
$result = $this->getFacade()->methodName($param);

// Getting Facade from Locator
$facade = $this->getLocator()
    ->moduleName()
    ->facade();

// Using in Business Layer
$result = $this->getFactory()
    ->createSomeManager()
    ->doSomething();
```

## URL Generation in Twig

```twig
{# Named route #}
<a href="{{ url('route-name') }}">Link</a>

{# With parameters #}
<a href="{{ url('route-name', {'id': item.id}) }}">Link</a>

{# Module/Controller/Action #}
<a href="{{ url('module/controller/action') }}">Link</a>

{# Current route #}
{{ path('_current') }}
```

## Common Escaping in Twig

```twig
{# Escape for HTML #}
{{ variable | escape }}
{{ variable | e }}

{# Escape for JavaScript #}
{{ variable | escape('js') }}

{# Escape for URL #}
{{ variable | escape('url') }}

{# Safe HTML (use carefully) #}
{{ variable | raw }}

{# Trim whitespace #}
{{ variable | trim }}

{# Uppercase/Lowercase #}
{{ variable | upper }}
{{ variable | lower }}

{# Default value #}
{{ variable | default('N/A') }}
```

## Testing Patterns

```php
// Unit test structure
class SomeTest extends Unit
{
    protected function setUp(): void
    {
        parent::setUp();
        // Setup
    }

    protected function tearDown(): void
    {
        // Cleanup
        parent::tearDown();
    }

    public function testSomethingDoesAction(): void
    {
        // Arrange
        $input = $this->createTestData();

        // Act
        $result = $subject->method($input);

        // Assert
        $this->assertSame($expected, $result);
    }
}

// Common assertions
$this->assertTrue($condition);
$this->assertFalse($condition);
$this->assertNull($value);
$this->assertNotNull($value);
$this->assertEmpty($value);
$this->assertNotEmpty($value);
$this->assertSame($expected, $actual);
$this->assertEquals($expected, $actual);
$this->assertCount(3, $array);
$this->assertContains($needle, $haystack);
$this->assertIsArray($value);
$this->assertIsString($value);
$this->assertInstanceOf(ClassName::class, $object);
$this->expectException(Exception::class);
```

## Git Branches for Exercises

```bash
# Basics Modules
git checkout ilt/202512.0/basics/hello-world-back-office/skeleton
git checkout ilt/202512.0/basics/hello-world-back-office/complete

git checkout ilt/202512.0/basics/data-transfer-object/skeleton
git checkout ilt/202512.0/basics/data-transfer-object/complete

git checkout ilt/202512.0/basics/message-table-schema/skeleton
git checkout ilt/202512.0/basics/message-table-schema/complete

git checkout ilt/202512.0/basics/supplier-table-schema/skeleton
git checkout ilt/202512.0/basics/module-layers/skeleton
git checkout ilt/202512.0/basics/module-layers/complete

# Intermediate Modules
git checkout ilt/202512.0/intermediate/back-office/skeleton
git checkout ilt/202512.0/intermediate/back-office/complete

git checkout ilt/202512.0/intermediate/data-import/skeleton
git checkout ilt/202512.0/intermediate/data-import/complete

git checkout ilt/202512.0/intermediate/publish-synchronize/skeleton
git checkout ilt/202512.0/intermediate/publish-synchronize/complete

git checkout ilt/202512.0/intermediate/search/skeleton
git checkout ilt/202512.0/intermediate/search/complete

git checkout ilt/202512.0/intermediate/glue-storefront/skeleton
git checkout ilt/202512.0/intermediate/glue-storefront/complete

git checkout ilt/202512.0/intermediate/oms/skeleton
git checkout ilt/202512.0/intermediate/oms/complete
```

## Debugging Tips

```php
// Dump variables
dump($variable);  // In controller/model
{{ dump(variable) }}  // In Twig template

// Using logging
$logger->error('Error message', ['context' => $data]);

// Checking if method exists
if (method_exists($object, 'methodName')) {
    $object->methodName();
}

// Debugging queries
$query = SomeQuery::create();
echo $query->toString();  // Show SQL

// Check type
var_dump(gettype($variable));
get_class($object);
```

## Performance Tips

1. **Eager Loading**
   ```php
   $items = ItemQuery::create()
       ->useRelatedQuery()
       ->endUse()
       ->find();
   ```

2. **Limiting Results**
   ```php
   $items = ItemQuery::create()
       ->limit(20)
       ->offset(($page - 1) * 20)
       ->find();
   ```

3. **Adding Indexes**
   ```xml
   <index name="idx_created_at">
       <index-column name="created_at"/>
   </index>
   ```

4. **Caching**
   ```php
   $cache->get($key, function() {
       return expensiveOperation();
   });
   ```

5. **Avoiding N+1**
   - Load related data upfront
   - Use joins when possible
   - Batch process data

## Security Checklist

- [ ] Escape all template output
- [ ] Validate user input on server
- [ ] Use CSRF protection (AbstractController provides it)
- [ ] Check user permissions before operations
- [ ] Don't expose sensitive data in APIs
- [ ] Use prepared statements (Propel/Doctrine do this)
- [ ] Sanitize file uploads
- [ ] Log security events
- [ ] Use HTTPS in production
- [ ] Hash passwords (never store plaintext)

---

**Quick Reference Version:** 1.0
**Last Updated:** February 2025
