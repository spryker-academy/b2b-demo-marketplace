# Spryker Backend Development - Student Exercise Guide
## Hands-On Training Workbook

> **Purpose:** Step-by-step exercises with hints (NO solutions)  
> **Use:** Work through exercises, compare with `/complete` branch when done  
> **Duration:** 3-5 days full-time

---

## 📋 How to Use This Guide

1. **Checkout skeleton branch** for the exercise
2. **Read the exercise objectives** below
3. **Look for TODO comments** in the code
4. **Follow hints** when stuck
5. **Test your implementation** 
6. **Compare with complete branch** when done

---

## Table of Contents

### Part 1: Basics
1. [Hello World Back Office](#exercise-1-hello-world-back-office) - 30 min
2. [Data Transfer Objects](#exercise-2-data-transfer-objects) - 45 min
3. [Database Schema - Message](#exercise-3-database-schema-message) - 1 hour
4. [Database Schema - Supplier](#exercise-4-database-schema-supplier) - 1 hour
5. [Module Layers](#exercise-5-module-layers-architecture) - 2 hours

### Part 2: Intermediate
6. [Back Office CRUD](#exercise-6-back-office-crud) - 3 hours
7. [Data Import](#exercise-7-data-import) - 2 hours
8. [Publish & Synchronize](#exercise-8-publish--synchronize) - 3 hours
9. [Elasticsearch](#exercise-9-elasticsearch-integration) - 3 hours
10. [API Platform](#exercise-10-api-platform-glue) - 3 hours
11. [OMS](#exercise-11-order-management-system) - 4 hours

---

# Part 1: Basics

## Exercise 1: Hello World Back Office

**Branch:** `ilt/202512.0/basics/hello-world-back-office/skeleton`  
**Time:** 30 minutes  
**Difficulty:** ⭐☆☆☆☆

### 🎯 Learning Objectives
- Understand Spryker module structure
- Create a Zed controller
- Create a Twig template
- Access Back Office pages via URL

### 📝 What You Need to Do

#### Task 1: Create Module Structure
Create the following directory structure:
```
src/Pyz/Zed/HelloWorld/
├── Communication/
│   └── Controller/
│       └── IndexController.php
└── Presentation/
    └── Index/
        └── index.twig
```

**Hint:** Use `mkdir -p` command to create nested directories

#### Task 2: Implement Controller

**File to create:** `src/Pyz/Zed/HelloWorld/Communication/Controller/IndexController.php`

**Your tasks:**
1. Create a class named `IndexController`
2. Make it extend `AbstractController` from Spryker
3. Add a method named `indexAction()`
4. Return an array with a 'message' key
5. Add type hints for the return type

**Hints:**
- Controllers are in the namespace: `Pyz\Zed\{Module}\Communication\Controller`
- Import: `use Spryker\Zed\Kernel\Communication\Controller\AbstractController;`
- Action methods must return `array`
- The array you return will be available in Twig templates

**Expected array structure:**
```php
[
    'message' => 'Some text here',
    'timestamp' => 'Optional: current time'
]
```

#### Task 3: Create Twig Template

**File to create:** `src/Pyz/Zed/HelloWorld/Presentation/Index/index.twig`

**Your tasks:**
1. Extend the Back Office layout
2. Override the `content` block
3. Display the message from the controller
4. Use Spryker UI components (spy-card)

**Hints:**
- Extend: `{% extends '@Gui/Layout/layout.twig' %}`
- Define content: `{% block content %} ... {% endblock %}`
- Access variables: `{{ message }}`
- Use `<div class="spy-card">` for consistent styling

**Twig structure to use:**
```twig
{% extends '...' %}

{% block content %}
    <div class="spy-card">
        <div class="spy-card__header">
            <!-- Title here -->
        </div>
        <div class="spy-card__body">
            <!-- Display message variable here -->
        </div>
    </div>
{% endblock %}
```

### ✅ How to Test

1. **Access the page:**
   ```
   http://backoffice.eu.spryker.local/hello-world
   ```

2. **Expected result:**
   - You should see the Back Office navigation
   - A card should display with your message
   - No errors in the browser console

3. **Troubleshooting:**
   - **404 Error?** Check your namespace matches the directory structure
   - **Blank page?** Check `var/logs/ZED/` for PHP errors
   - **Template not found?** Verify Presentation path matches controller/action names

### 🎓 Key Concepts to Remember

- URL pattern: `/{module}/{controller}/{action}`
- `/hello-world` maps to `HelloWorld` module, `Index` controller, `index` action
- Controllers return arrays that become Twig variables
- Template location must match: `Presentation/{Controller}/{action}.twig`

---

## Exercise 2: Data Transfer Objects

**Branch:** `ilt/202512.0/basics/data-transfer-object/skeleton`  
**Time:** 45 minutes  
**Difficulty:** ⭐⭐☆☆☆

### 🎯 Learning Objectives
- Define Transfer Object schemas in XML
- Generate Transfer classes
- Use Transfer Objects in code
- Understand type-safe data handling

### 📝 What You Need to Do

#### Task 1: Define Transfer Schema

**File to create:** `src/Pyz/Shared/Message/Transfer/message.transfer.xml`

**Your tasks:**
1. Create XML file with proper structure
2. Define a Transfer named "Message"
3. Add properties: idMessage, text, author, createdAt, updatedAt
4. Use correct data types for each property

**Hints:**
- XML must start with: `<?xml version="1.0"?>`
- Root element: `<transfers xmlns="spryker:transfer-01" ...>`
- Each transfer: `<transfer name="Message">`
- Each property: `<property name="..." type="..."/>`
- Common types: `int`, `string`, `bool`, `array`

**XML structure template:**
```xml
<?xml version="1.0"?>
<transfers xmlns="spryker:transfer-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">

    <transfer name="YourTransferName">
        <!-- Add properties here -->
    </transfer>

</transfers>
```

**Property types to use:**
- ID fields: `int`
- Text fields: `string`
- Date fields: `string` (formatted date)

#### Task 2: Generate Transfer Classes

**Command to run:**
```bash
console transfer:generate
```

**What this does:**
- Generates `MessageTransfer.php` in `src/Generated/Shared/Transfer/`
- Creates getters and setters for all properties
- Adds helper methods (toArray, fromArray, etc.)

**Verify generation:**
```bash
ls -la src/Generated/Shared/Transfer/MessageTransfer.php
```

#### Task 3: Use Transfer in Controller

**File to create:** `src/Pyz/Zed/Message/Communication/Controller/IndexController.php`

**Your tasks:**
1. Import the generated MessageTransfer class
2. Create a new MessageTransfer instance
3. Set values using setter methods
4. Return it in the controller array

**Hints:**
- Import: `use Generated\Shared\Transfer\MessageTransfer;`
- Create instance: `new MessageTransfer()`
- Use fluent setters: `->setText('...')->setAuthor('...')`
- Date format: `date('Y-m-d H:i:s')`

**Fluent setter pattern:**
```php
$transfer = (new SomeTransfer())
    ->setProperty1('value1')
    ->setProperty2('value2')
    ->setProperty3('value3');
```

#### Task 4: Display Transfer in Template

**File to create:** `src/Pyz/Zed/Message/Presentation/Index/index.twig`

**Your tasks:**
1. Extend the Gui layout
2. Access transfer object properties in Twig
3. Display: text, author, createdAt

**Hints:**
- Access transfer properties: `{{ message.text }}`
- Transfer getters work in Twig: `{{ transferObject.propertyName }}`
- Use `<strong>` tags for labels

### ✅ How to Test

1. **After generation, check:**
   ```bash
   # Should show the generated class
   cat src/Generated/Shared/Transfer/MessageTransfer.php | head -50
   ```

2. **Access the page:**
   ```
   http://backoffice.eu.spryker.local/message
   ```

3. **Expected result:**
   - Page displays with all three properties
   - Text, author, and timestamp should appear
   - No PHP errors

4. **Common issues:**
   - **Class not found?** Run `console transfer:generate` again
   - **Property not accessible?** Check property name spelling in XML
   - **Cache issues?** Run `console cache:empty-all`

### 🎓 Key Concepts to Remember

- Transfer schemas are defined in `Shared` layer (cross-layer usage)
- Always run `transfer:generate` after modifying XML
- Transfer Objects are immutable and type-safe
- Use `toArray()` to convert to array, `fromArray()` to create from array

---

## Exercise 3: Database Schema - Message Table

**Branch:** `ilt/202512.0/basics/message-table-schema/skeleton`  
**Time:** 1 hour  
**Difficulty:** ⭐⭐☆☆☆

### 🎯 Learning Objectives
- Define Propel database schemas
- Generate entity and query classes
- Run database migrations
- Perform CRUD operations

### 📝 What You Need to Do

#### Task 1: Create Schema Definition

**File to create:** `src/Pyz/Zed/Message/Persistence/Propel/Schema/pyz_message.schema.xml`

**Your tasks:**
1. Define a table named `pyz_message`
2. Add primary key: `id_message` (INTEGER, auto-increment)
3. Add columns: text (LONGVARCHAR, required), author (VARCHAR), created_at, updated_at
4. Add timestampable behavior

**Hints:**
- Root element: `<database xmlns="spryker:schema-01" name="zed">`
- Namespace: `Orm\Zed\Message\Persistence`
- Primary key needs: `primaryKey="true" autoIncrement="true"`
- Required fields: `required="true"`
- VARCHAR needs size: `size="255"`

**Column types to use:**
- IDs: `INTEGER`
- Short text: `VARCHAR` (with size attribute)
- Long text: `LONGVARCHAR`
- Timestamps: `TIMESTAMP`
- Booleans: `BOOLEAN`

**Timestampable behavior structure:**
```xml
<behavior name="timestampable">
    <parameter name="create_column" value="created_at"/>
    <parameter name="update_column" value="updated_at"/>
</behavior>
```

#### Task 2: Generate Models and Migrate

**Commands to run:**
```bash
# Generate Propel models
console propel:install

# Run database migration
console propel:migrate
```

**What gets generated:**
- `SpyMessage.php` - Entity class
- `SpyMessageQuery.php` - Query builder class
- Both in: `src/Orm/Zed/Message/Persistence/`

**Verify migration:**
```bash
# Check if table exists in database
console propel:model:build
```

#### Task 3: Test CRUD Operations

**Where to test:** Create a test controller action

**Your tasks:**
1. Create a new message entity
2. Save it to database
3. Query it back
4. Display results

**Hints:**
- Import: `use Orm\Zed\Message\Persistence\SpyMessage;`
- Create: `$entity = new SpyMessage();`
- Set data: `$entity->setText('...')`
- Save: `$entity->save();`
- Query: `use Orm\Zed\Message\Persistence\SpyMessageQuery;`
- Find all: `SpyMessageQuery::create()->find();`

**Basic entity operations:**
```php
// Create
$entity = new SpyEntityName();
$entity->setProperty('value');
$entity->save();

// Read
$entity = SpyEntityNameQuery::create()
    ->findOneByIdField($id);

// Update
$entity->setProperty('new value');
$entity->save();

// Delete
$entity->delete();
```

### ✅ How to Test

1. **Check generated files:**
   ```bash
   ls -la src/Orm/Zed/Message/Persistence/Spy*
   ```

2. **Verify database table:**
   ```bash
   # Connect to database and check
   docker exec -it <container> psql -U postgres -d <db> -c "\d pyz_message"
   ```

3. **Test in controller:**
   - Create test action that saves a message
   - Query and display all messages
   - Should see data persist across requests

4. **Common issues:**
   - **Schema not found?** Check file path exactly matches pattern
   - **Migration fails?** Check XML syntax with validator
   - **Class not found?** Run `console propel:install` again

### 🎓 Key Concepts to Remember

- Schema files must be in: `Persistence/Propel/Schema/`
- Table names use `pyz_` prefix for project tables
- Always use behaviors (timestampable) for audit trails
- Propel generates both Entity (single record) and Query (finder) classes
- Entities are active records - call `save()` to persist

---

## Exercise 4: Database Schema - Supplier Table

**Branch:** `ilt/202512.0/basics/supplier-table-schema/skeleton`  
**Time:** 1 hour  
**Difficulty:** ⭐⭐⭐☆☆

### 🎯 Learning Objectives
- Create complex schemas with relationships
- Define foreign keys
- Implement one-to-many relationships
- Work with related entities

### 📝 What You Need to Do

#### Task 1: Define Supplier and Location Tables

**File to create:** `src/Pyz/Zed/Supplier/Persistence/Propel/Schema/pyz_supplier.schema.xml`

**Your tasks:**
1. Define `pyz_supplier` table with: id_supplier, name, email, phone, is_active
2. Define `pyz_supplier_location` table with: id, fk_supplier, address, city, country, zip_code, is_primary
3. Add foreign key from location to supplier
4. Add unique constraint on supplier name
5. Add indexes for frequently queried columns

**Hints:**

**Unique constraint structure:**
```xml
<unique name="unique-name">
    <unique-column name="column_name"/>
</unique>
```

**Index structure:**
```xml
<index name="index-name">
    <index-column name="column_name"/>
</index>
```

**Foreign key structure:**
```xml
<foreign-key name="fk-name" foreignTable="pyz_supplier">
    <reference local="fk_supplier" foreign="id_supplier"/>
</foreign-key>
```

**Event behavior (for Publish & Sync):**
```xml
<behavior name="event">
    <parameter name="pyz_supplier_all" column="*"/>
</behavior>
```

#### Task 2: Work with Relationships

**Your tasks:**
1. Create a supplier with multiple locations
2. Save using the relationship
3. Query supplier and access locations
4. Test cascade operations

**Hints:**
- Add location to supplier: `$supplier->addSpySupplierLocation($location)`
- Access locations: `$supplier->getSpySupplierLocations()`
- Saving supplier auto-saves related locations
- Use `->joinWithSpySupplierLocation()` for efficient queries

**Relationship pattern:**
```php
$parent = new SpyParent();
$parent->setName('Parent');

$child1 = new SpyChild();
$child1->setName('Child 1');

$child2 = new SpyChild();
$child2->setName('Child 2');

$parent->addSpyChild($child1);
$parent->addSpyChild($child2);

$parent->save(); // Saves parent and all children

// Later, access children
foreach ($parent->getSpyChildren() as $child) {
    echo $child->getName();
}
```

### ✅ How to Test

1. **Generate and migrate:**
   ```bash
   console propel:install
   console propel:migrate
   ```

2. **Test relationships:**
   - Create supplier with 2+ locations
   - Save once (should save all)
   - Query supplier by ID
   - Count locations (should match what you added)

3. **Verify foreign key:**
   - Try deleting supplier with locations
   - Should either prevent deletion or cascade (depending on configuration)

4. **Check unique constraint:**
   - Try creating two suppliers with same name
   - Should get constraint violation error

### 🎓 Key Concepts to Remember

- One-to-many: One supplier has many locations
- Foreign key in "many" table points to "one" table
- Unique constraints prevent duplicates
- Indexes improve query performance
- Event behavior triggers publish/sync events

---

## Exercise 5: Module Layers Architecture

**Branch:** `ilt/202512.0/basics/module-layers/skeleton`  
**Time:** 2 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### 🎯 Learning Objectives
- Understand complete module architecture
- Implement Facade pattern
- Separate read (Repository) from write (EntityManager)
- Use Factories for dependency injection

### 📝 What You Need to Do

#### Task 1: Create Business Layer Facade

**Files to create:**
- `Business/SupplierFacadeInterface.php`
- `Business/SupplierFacade.php`

**Your tasks:**
1. Define interface with methods: `findSupplierById()`, `createSupplier()`
2. Implement facade extending AbstractFacade
3. Delegate to Reader/Writer from Factory

**Hints:**
- Facade is the **public API** of Business layer
- Interface defines the contract
- Implementation delegates to models via Factory
- Use `@method` annotations for Factory/Repository/EntityManager

**Facade structure:**
```php
interface ModuleFacadeInterface
{
    public function methodName(TransferType $transfer): ReturnType;
}

class ModuleFacade extends AbstractFacade implements ModuleFacadeInterface
{
    public function methodName(TransferType $transfer): ReturnType
    {
        return $this->getFactory()
            ->createSomeModel()
            ->doSomething($transfer);
    }
}
```

#### Task 2: Create Business Factory

**File to create:** `Business/SupplierBusinessFactory.php`

**Your tasks:**
1. Extend AbstractBusinessFactory
2. Create methods: `createSupplierReader()`, `createSupplierWriter()`
3. Inject Repository/EntityManager into models

**Hints:**
- Factory creates all Business layer objects
- Use `@method` annotations for type hints
- Return interfaces, not concrete classes
- Pass dependencies via constructor

**Factory pattern:**
```php
class ModuleBusinessFactory extends AbstractBusinessFactory
{
    public function createSomeModel(): SomeModelInterface
    {
        return new SomeModel(
            $this->getRepository(),      // Read access
            $this->getEntityManager()    // Write access
        );
    }
}
```

#### Task 3: Create Persistence Layer

**Files to create:**
- `Persistence/SupplierRepositoryInterface.php`
- `Persistence/SupplierRepository.php`
- `Persistence/SupplierEntityManagerInterface.php`
- `Persistence/SupplierEntityManager.php`

**Your tasks (Repository):**
1. Define read operations in interface
2. Implement using Propel Query classes
3. Map entities to Transfer objects

**Your tasks (EntityManager):**
1. Define write operations in interface
2. Implement create/update/delete
3. Map Transfer objects to entities

**Hints:**
- Repository = READ ONLY (SELECT queries)
- EntityManager = WRITE ONLY (INSERT/UPDATE/DELETE)
- Always return Transfer objects, never entities
- Use Mapper for entity ↔ transfer conversion

**Repository pattern:**
```php
class ModuleRepository extends AbstractRepository
{
    public function findById(int $id): ?ModuleTransfer
    {
        $entity = $this->getFactory()
            ->createQuery()
            ->findOneById($id);

        if ($entity === null) {
            return null;
        }

        return $this->mapEntityToTransfer($entity);
    }
}
```

**EntityManager pattern:**
```php
class ModuleEntityManager extends AbstractEntityManager
{
    public function create(ModuleTransfer $transfer): ModuleTransfer
    {
        $entity = new SpyModule();
        $entity->fromArray($transfer->toArray());
        $entity->save();

        return $this->mapEntityToTransfer($entity);
    }
}
```

#### Task 4: Create Models (Reader/Writer)

**Files to create:**
- `Business/Reader/SupplierReader.php`
- `Business/Writer/SupplierWriter.php`

**Your tasks:**
1. Reader: Implement read operations using Repository
2. Writer: Implement write operations using EntityManager
3. Add business logic validation

**Hints:**
- Models contain business logic
- Reader uses Repository (injected via constructor)
- Writer uses EntityManager (injected via constructor)
- Models are created by Factory

### ✅ How to Test

1. **Test through Facade:**
   ```php
   $facade = new SupplierFacade();
   
   // Create
   $supplier = (new SupplierTransfer())
       ->setName('Test')
       ->setEmail('test@test.com');
   $created = $facade->createSupplier($supplier);
   
   // Read
   $found = $facade->findSupplierById($created->getIdSupplier());
   ```

2. **Verify layers:**
   - Controller calls Facade only
   - Facade calls Factory to get models
   - Models call Repository/EntityManager
   - Repository/EntityManager use Propel entities

3. **Check separation:**
   - Repository should have NO save/delete methods
   - EntityManager should have NO find methods

### 🎓 Key Concepts to Remember

- **Facade**: Public API, orchestrates business logic
- **Factory**: Creates objects, handles dependencies
- **Repository**: Read operations only
- **EntityManager**: Write operations only
- **Models**: Business logic, use Repository/EntityManager
- Always use Transfer objects in public APIs
- Never expose Propel entities outside Persistence layer

---

# Part 2: Intermediate

## Exercise 6: Back Office CRUD

**Branch:** `ilt/202512.0/intermediate/back-office/skeleton`  
**Time:** 3 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### 🎯 Learning Objectives
- Build complete CRUD functionality
- Create Symfony forms with validation
- Implement data tables
- Handle form submissions

### 📝 What You Need to Do

#### Task 1: Create Data Table

**File to create:** `Communication/Table/SupplierTable.php`

**Your tasks:**
1. Extend AbstractTable
2. Configure columns, searchable fields, sortable fields
3. Implement prepareData() method
4. Add action buttons (Edit, Delete)
5. Format boolean values as labels

**Hints:**
- Use constants for column names
- Configure in `configure()` method
- Use `runQuery()` to execute Propel query
- Generate buttons with `generateEditButton()`, `generateRemoveButton()`
- Return array of arrays for table data

**Table structure:**
```php
class SomeTable extends AbstractTable
{
    const COL_ID = 'id';
    const COL_NAME = 'name';

    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([...]);
        $config->setSearchable([...]);
        $config->setSortable([...]);
        return $config;
    }

    protected function prepareData(TableConfiguration $config)
    {
        $results = $this->runQuery($this->query, $config);
        // Map to array of arrays
        return $mappedResults;
    }
}
```

#### Task 2: Create Form Type

**File to create:** `Communication/Form/SupplierForm.php`

**Your tasks:**
1. Extend AbstractType
2. Add fields: name, email, phone, isActive
3. Add validation constraints
4. Use appropriate field types

**Hints:**
- Add fields in `buildForm()` method
- Use constants for field names
- Field types: TextType, EmailType, CheckboxType
- Constraints: NotBlank, Email, Length

**Form structure:**
```php
class SomeForm extends AbstractType
{
    const FIELD_NAME = 'name';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNameField($builder);
        // Add other fields
    }

    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
```

#### Task 3: Create CRUD Actions

**File:** `Communication/Controller/IndexController.php`

**Actions to implement:**
- `indexAction()` - List view with table
- `tableAction()` - AJAX endpoint for table data
- `createAction()` - Create form and handler
- `editAction()` - Edit form and handler
- `deleteAction()` - Delete with confirmation

**Hints:**
- Form handling: `$form->handleRequest($request)`
- Check submission: `$form->isSubmitted() && $form->isValid()`
- Get data: `$form->getData()`
- Redirect after POST: `$this->redirectResponse('/supplier')`
- Success message: `$this->addSuccessMessage('...')`

**Controller action pattern:**
```php
public function createAction(Request $request)
{
    $form = $this->getFactory()->createForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        // Process data via Facade
        $this->getFacade()->create($transfer);
        $this->addSuccessMessage('Created!');
        return $this->redirectResponse('/module');
    }

    return $this->viewResponse([
        'form' => $form->createView(),
    ]);
}
```

#### Task 4: Create Templates

**Templates to create:**
- `Presentation/Index/index.twig` - List view
- `Presentation/Index/create.twig` - Create form
- `Presentation/Index/edit.twig` - Edit form

**Your tasks:**
1. Extend Gui layout
2. Render table with AJAX endpoint
3. Render forms with proper styling
4. Add navigation buttons

**Hints:**
- Table: `{{ table | raw }}`
- Form: `{{ form_start(form) }} ... {{ form_end(form) }}`
- Form fields: `{{ form_row(form.fieldName) }}`
- Submit button: `{{ form_widget(form.submit) }}`

### ✅ How to Test

1. **List page:**
   ```
   http://backoffice.eu.spryker.local/supplier
   ```
   - Should show table with data
   - Search should work
   - Sorting should work

2. **Create:**
   - Click "Create" button
   - Fill form
   - Submit
   - Should redirect to list with success message

3. **Edit:**
   - Click "Edit" on a row
   - Form pre-filled with data
   - Change and submit
   - Verify changes in list

4. **Delete:**
   - Click "Delete" on a row
   - Confirm deletion
   - Row should disappear

### 🎓 Key Concepts to Remember

- Tables fetch data via AJAX for better performance
- Forms handle validation automatically
- Always redirect after POST (PRG pattern)
- Use Flash messages for user feedback
- Keep controllers thin - business logic in Facade

---

## Exercise 7: Data Import

**Branch:** `ilt/202512.0/intermediate/data-import/skeleton`  
**Time:** 2 hours  
**Difficulty:** ⭐⭐⭐☆☆

### 🎯 Learning Objectives
- Configure data import from CSV
- Create import steps (pipeline)
- Transform and validate data
- Run batch imports

### 📝 What You Need to Do

#### Task 1: Create CSV File

**File to create:** `data/import/common/common/supplier.csv`

**Your tasks:**
1. Create CSV with headers: name, email, phone, is_active
2. Add at least 5 sample suppliers
3. Use proper CSV format (quoted strings)

**Hints:**
- First row is header
- Wrap text fields in quotes
- Boolean: use 1 for true, 0 for false
- No spaces after commas in header

**CSV example:**
```csv
header1,header2,header3
"Value 1","Value 2","Value 3"
"Value A","Value B","Value C"
```

#### Task 2: Configure Import

**File to modify:** `src/Pyz/Zed/DataImport/DataImportConfig.php`

**Your tasks:**
1. Define import type constant: `IMPORT_TYPE_SUPPLIER`
2. Add method: `getSupplierDataImportFilePath()`
3. Return path to CSV file

**Hints:**
- Use `$this->getDataImportRootPath()` for base path
- Path format: `'common/common/supplier.csv'`
- Import type is used in console command

**Config pattern:**
```php
class DataImportConfig extends SprykerDataImportConfig
{
    public const IMPORT_TYPE_MODULE = 'module-name';

    public function getModuleDataImportFilePath(): string
    {
        return $this->getDataImportRootPath() . 'path/to/file.csv';
    }
}
```

#### Task 3: Create Writer Step

**File to create:** `Business/Model/Supplier/SupplierWriterStep.php`

**Your tasks:**
1. Implement DataImportStepInterface
2. Define constants for CSV column names
3. In execute(), find or create entity
4. Set entity data from DataSet
5. Save entity

**Hints:**
- Use `findOneOrCreate()` to handle duplicates
- Access CSV data: `$dataSet[static::KEY_NAME]`
- Cast boolean: `(bool)$dataSet[static::KEY_IS_ACTIVE]`
- Save: `$entity->save()`

**Writer step pattern:**
```php
class SomeWriterStep implements DataImportStepInterface
{
    public const KEY_COLUMN = 'column_name';

    public function execute(DataSetInterface $dataSet): void
    {
        $entity = SpyEntityQuery::create()
            ->filterByField($dataSet[static::KEY_COLUMN])
            ->findOneOrCreate();

        $entity->fromArray($dataSet->getArrayCopy());
        $entity->save();
    }
}
```

#### Task 4: Register Importer

**File to modify:** `Business/DataImportBusinessFactory.php`

**Your tasks:**
1. Create method: `createSupplierImporter()`
2. Get CSV data importer from config
3. Add SupplierWriterStep
4. Return importer

**Hints:**
- Use `getCsvDataImporterFromConfig()`
- Build config with: `buildImporterConfiguration()`
- Chain steps: `->addDataImportStep()`

**Importer registration:**
```php
public function createModuleImporter(): DataImporterInterface
{
    $dataImporter = $this->getCsvDataImporterFromConfig(
        $this->getConfig()->buildImporterConfiguration(
            $this->getConfig()->getModuleDataImportFilePath(),
            DataImportConfig::IMPORT_TYPE_MODULE
        )
    );

    $dataImporter->addDataImportStep(new SomeWriterStep());

    return $dataImporter;
}
```

### ✅ How to Test

1. **Run import:**
   ```bash
   console data:import supplier
   ```

2. **Expected output:**
   - Should show progress
   - Should report number of imported rows
   - No errors

3. **Verify in database:**
   ```bash
   # Check if data exists
   docker exec -it <container> psql -U postgres -d <db> -c "SELECT * FROM pyz_supplier"
   ```

4. **Verify in Back Office:**
   - Go to supplier list
   - Should see imported suppliers

5. **Test re-import:**
   - Run import again
   - Should update existing (not duplicate)

### 🎓 Key Concepts to Remember

- Import steps form a pipeline
- Each step processes DataSet
- Use `findOneOrCreate()` for idempotency
- Import type must match in config and command
- CSV first row is header (automatically mapped)

---

## Exercise 8: Publish & Synchronize

**Branch:** `ilt/202512.0/intermediate/publish-synchronize/skeleton`  
**Time:** 3 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### 🎯 Learning Objectives
- Implement event-driven data synchronization
- Create publisher plugins
- Configure storage tables
- Sync data to Elasticsearch/Redis

### 📝 What You Need to Do

#### Task 1: Create Storage Schema

**File to create:** `src/Pyz/Zed/SupplierSearch/Persistence/Propel/Schema/pyz_supplier_search.schema.xml`

**Your tasks:**
1. Define table: `spy_supplier_search`
2. Add columns: id (PK), fk_supplier (FK), data (JSON), key
3. Add synchronization behavior
4. Add timestampable behavior

**Hints:**
- Data column type: `LONGVARCHAR`
- Key column stores: `supplier:{id}`
- Sync behavior params: resource, queue_group, key_suffix_column

**Synchronization behavior structure:**
```xml
<behavior name="synchronization">
    <parameter name="resource" value="resource-name"/>
    <parameter name="store" required="false"/>
    <parameter name="locale" required="false"/>
    <parameter name="key_suffix_column" value="fk_column_name"/>
    <parameter name="queue_group" value="sync.search.resource"/>
</behavior>
```

#### Task 2: Create Publisher Trigger Plugin

**File to create:** `Communication/Plugin/Publisher/SupplierPublisherTriggerPlugin.php`

**Your tasks:**
1. Implement PublisherTriggerPluginInterface
2. getData(): Query suppliers with pagination
3. getResourceName(): Return resource name from config
4. getEventName(): Return publish event name
5. getIdColumnName(): Return 'id_supplier'

**Hints:**
- Use SpySupplierQuery with offset/limit
- Convert entities to Transfer objects
- Return array of transfers

**Trigger plugin pattern:**
```php
class SomePublisherTriggerPlugin extends AbstractPlugin 
    implements PublisherTriggerPluginInterface
{
    public function getData(int $offset, int $limit): array
    {
        $entities = SpyEntityQuery::create()
            ->offset($offset)
            ->limit($limit)
            ->find();

        // Map to transfers
        return $transfers;
    }

    public function getResourceName(): string
    {
        return 'resource-name';
    }

    public function getEventName(): string
    {
        return 'event.name';
    }
}
```

#### Task 3: Create Event Subscriber Plugin

**File to create:** `Communication/Plugin/Publisher/SupplierSearchPublisherPlugin.php`

**Your tasks:**
1. Implement PublisherPluginInterface
2. handleBulk(): Extract IDs and call Facade
3. getSubscribedEvents(): Return array of event names

**Hints:**
- Use EventBehaviorFacade to extract IDs
- Call `$this->getFacade()->publishSuppliers($ids)`
- Subscribe to: publish, create, update events

**Subscriber pattern:**
```php
class SomePublisherPlugin extends AbstractPlugin 
    implements PublisherPluginInterface
{
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $ids = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventTransfers);

        $this->getFacade()->publish($ids);
    }

    public function getSubscribedEvents(): array
    {
        return [
            'event.entity.create',
            'event.entity.update',
        ];
    }
}
```

#### Task 4: Create Search Writer

**File to create:** `Business/Writer/SupplierSearchWriter.php`

**Your tasks:**
1. publishSuppliers(): Loop through IDs
2. publishSupplier(): For each ID:
   - Find or create search entity
   - Build search data JSON
   - Set data and key
   - Save entity

**Hints:**
- Use SpySupplierSearchQuery `findOneOrCreate()`
- Search data structure: type, id, name, email, search-result-data
- Key format: `supplier:{id}`
- JSON encode: `json_encode($data)`

**Search data structure:**
```php
$searchData = [
    'type' => 'resource-type',
    'id_field' => $id,
    'field1' => 'value1',
    'search-result-data' => [
        // All fields needed for display
    ],
];
```

#### Task 5: Register Plugins

**Files to modify:**
- `PublisherDependencyProvider.php`
- Register trigger plugin in `getPublisherTriggerPlugins()`
- Register subscriber in `getPublisherPlugins()`

**Hints:**
- Add to array returned by method
- Trigger plugins run on `publish:trigger-events`
- Subscriber plugins run on entity changes

### ✅ How to Test

1. **Generate and migrate:**
   ```bash
   console propel:install
   console propel:migrate
   ```

2. **Trigger publish:**
   ```bash
   console publish:trigger-events -r supplier
   ```

3. **Check storage table:**
   ```bash
   # Should see data in spy_supplier_search
   docker exec -it <container> psql -U postgres -d <db> -c "SELECT * FROM spy_supplier_search LIMIT 5"
   ```

4. **Start queue worker:**
   ```bash
   console queue:worker:start
   ```

5. **Verify Elasticsearch:**
   ```bash
   curl -X GET "http://localhost:9200/supplier/_search?pretty"
   ```

6. **Test entity change:**
   - Update supplier via Back Office
   - Check spy_supplier_search updates
   - Verify Elasticsearch document updates

### 🎓 Key Concepts to Remember

- Publisher runs when entities change (via event behavior)
- Storage table acts as staging area
- Queue worker syncs to Elasticsearch/Redis
- search-result-data contains all display fields
- Trigger plugins handle bulk publishing
- Subscriber plugins handle entity events

---

## Exercise 9: Elasticsearch Integration

**Branch:** `ilt/202512.0/intermediate/search/skeleton`  
**Time:** 3 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### 🎯 Learning Objectives
- Build Elasticsearch queries
- Create result formatters
- Implement search client
- Add search functionality

### 📝 What You Need to Do

#### Task 1: Create Query Plugin

**File to create:** `Client/SupplierSearch/Plugin/Elasticsearch/Query/SupplierSearchQueryPlugin.php`

**Your tasks:**
1. Implement QueryInterface
2. Accept search string in constructor
3. Build BoolQuery with:
   - Type filter (match on type field)
   - Full-text search (match on name with fuzziness)
4. Set source fields

**Hints:**
- Use `BoolQuery` for combining conditions
- `addMust()` for required conditions
- `Match` for text matching
- `MatchAll` when no search string
- Fuzziness: `'AUTO'` for typo tolerance

**Query structure:**
```php
$boolQuery = new BoolQuery();

// Add type filter
$typeMatch = new Match();
$typeMatch->setField('type', 'resource-type');
$boolQuery->addMust($typeMatch);

// Add search
$searchMatch = new Match();
$searchMatch->setFieldQuery('field_name', $searchString);
$searchMatch->setFieldFuzziness('field_name', 'AUTO');
$boolQuery->addMust($searchMatch);

$query = new Query($boolQuery);
$query->setSource(['field1', 'field2']);
```

#### Task 2: Create Result Formatter Plugin

**File to create:** `Client/SupplierSearch/Plugin/Elasticsearch/ResultFormatter/SupplierSearchResultFormatterPlugin.php`

**Your tasks:**
1. Implement ResultFormatterPluginInterface
2. getName(): Return 'suppliers'
3. formatResult(): Map Elastica results to Transfer objects

**Hints:**
- Get total: `$searchResult->getTotalHits()`
- Iterate results: `$searchResult->getResults()`
- Get data: `$result->getSource()`
- Extract search-result-data
- Map to Transfer using fromArray()

**Formatter pattern:**
```php
public function formatResult(ResultSet $searchResult, array $requestParameters = [])
{
    $resultTransfer = new ResultTransfer();
    $resultTransfer->setTotalCount($searchResult->getTotalHits());

    foreach ($searchResult->getResults() as $result) {
        $data = $result->getSource();
        $transfer = $this->mapToTransfer($data);
        $resultTransfer->addItem($transfer);
    }

    return $resultTransfer;
}
```

#### Task 3: Create Search Client

**File to create:** `Client/SupplierSearch/SupplierSearchClient.php`

**Your tasks:**
1. Implement SupplierSearchClientInterface
2. searchSuppliers(): Use SearchClient to execute query
3. Return formatted results

**Hints:**
- Get query from factory: `createSupplierSearchQuery()`
- Get formatters from factory: `getSupplierSearchResultFormatters()`
- Use SearchClient: `$this->getFactory()->getSearchClient()->search()`
- Extract result by name: `$results['suppliers']`

**Search client pattern:**
```php
public function search(string $searchString, array $params = [])
{
    $query = $this->getFactory()->createQuery($searchString);
    $formatters = $this->getFactory()->getFormatters();

    $results = $this->getFactory()
        ->getSearchClient()
        ->search($query, [], $formatters);

    return $results['result-name'];
}
```

#### Task 4: Setup Mappings

**Your tasks:**
1. Define Elasticsearch index mapping
2. Configure analyzers for text fields
3. Set up proper field types

**Hints:**
- Run: `console search:setup:sources`
- Mapping file location depends on your setup
- Text fields need analyzer
- Keyword fields for exact matching

### ✅ How to Test

1. **Setup Elasticsearch:**
   ```bash
   console search:setup:sources
   console search:setup:source-map
   ```

2. **Publish data:**
   ```bash
   console publish:trigger-events -r supplier
   console queue:worker:start
   ```

3. **Test search:**
   - Create controller action that uses search client
   - Search for "acme"
   - Should return matching suppliers

4. **Test fuzzy search:**
   - Search for "amce" (typo)
   - Should still find "acme"

5. **Test empty search:**
   - Search with empty string
   - Should return all suppliers

### 🎓 Key Concepts to Remember

- Query plugins build Elasticsearch queries
- Result formatters convert ES results to Transfers
- BoolQuery combines multiple conditions
- Fuzziness handles typos
- search-result-data contains display fields
- Always set source fields to limit returned data

---

## Exercise 10: API Platform (Glue)

**Branch:** `ilt/202512.0/intermediate/glue-storefront/skeleton`  
**Time:** 3 hours  
**Difficulty:** ⭐⭐⭐⭐☆

### 🎯 Learning Objectives
- Create REST API endpoints
- Implement resource providers
- Map between Transfer and API attributes
- Handle GET/POST requests

### 📝 What You Need to Do

#### Task 1: Create API Attributes Transfer

**File to create:** `src/Pyz/Shared/SuppliersApi/Transfer/suppliers_api.transfer.xml`

**Your tasks:**
1. Define SuppliersApiAttributes transfer
2. Add properties: id, name, email, phone, isActive
3. Use camelCase for property names (API standard)

**Hints:**
- API attributes are separate from domain transfers
- Use camelCase (JavaScript convention)
- All properties should be nullable for PATCH support

**API transfer pattern:**
```xml
<transfer name="ModuleApiAttributes">
    <property name="id" type="int"/>
    <property name="fieldName" type="string"/>
    <property name="isActive" type="bool"/>
</transfer>
```

#### Task 2: Create Resource Provider

**File to create:** `Glue/SuppliersApi/Provider/SupplierResourceProvider.php`

**Your tasks:**
1. Extend AbstractResourceProvider
2. getResourceClass(): Return API attributes class
3. getOperations(): Define GET, GET collection, POST
4. Implement: getCollection(), get(), create()

**Hints:**
- GetCollection: Search all suppliers
- Get: Find by ID
- Create: Save new supplier
- Use Client/Facade for business logic
- Use Mapper to convert transfers

**Provider structure:**
```php
class SomeResourceProvider extends AbstractResourceProvider
{
    public function getResourceClass(): string
    {
        return ApiAttributesTransfer::class;
    }

    public function getOperations(): array
    {
        return [
            GetCollection::class => [
                'method' => 'GET',
                'path' => '/resources',
                'provider' => [$this, 'getCollection'],
            ],
            Get::class => [
                'method' => 'GET',
                'path' => '/resources/{id}',
                'provider' => [$this, 'get'],
            ],
            Post::class => [
                'method' => 'POST',
                'path' => '/resources',
                'provider' => [$this, 'create'],
            ],
        ];
    }

    public function getCollection(GlueRequestTransfer $request): array
    {
        // Search and return array of API attributes
    }

    public function get(string $id, GlueRequestTransfer $request): ?ApiAttributesTransfer
    {
        // Find by ID and return single API attributes
    }

    public function create(ApiAttributesTransfer $attributes, GlueRequestTransfer $request)
    {
        // Create and return API attributes
    }
}
```

#### Task 3: Create Mapper

**File to create:** `Glue/SuppliersApi/Processor/Mapper/SupplierMapper.php`

**Your tasks:**
1. mapSupplierTransferToApiAttributes(): Domain → API
2. mapApiAttributesToSupplierTransfer(): API → Domain
3. mapSupplierSearchResultToApiAttributes(): Search result → API array

**Hints:**
- Use `toArray(false, true)` for camelCase conversion
- Use `fromArray($data, true)` to populate
- Handle null values
- Map arrays for collections

**Mapper pattern:**
```php
class SomeMapper
{
    public function mapDomainToApi(DomainTransfer $domain): ApiTransfer
    {
        $api = new ApiTransfer();
        $api->fromArray($domain->toArray(false, true), true);
        return $api;
    }

    public function mapApiToDomain(ApiTransfer $api): DomainTransfer
    {
        $domain = new DomainTransfer();
        $domain->fromArray($api->toArray(), true);
        return $domain;
    }
}
```

#### Task 4: Register Provider

**File to modify:** `Glue/Backend/ApplicationServices.php`

**Your tasks:**
1. Add SupplierResourceProvider to getResourceProviders()

**Hints:**
- Simply add class name to array
- Order doesn't matter
- Provider is auto-instantiated

### ✅ How to Test

1. **Generate transfers:**
   ```bash
   console transfer:generate
   ```

2. **Test GET collection:**
   ```bash
   curl -X GET "http://glue.eu.spryker.local/suppliers"
   ```
   Expected: JSON array with all suppliers

3. **Test GET single:**
   ```bash
   curl -X GET "http://glue.eu.spryker.local/suppliers/1"
   ```
   Expected: JSON object with supplier data

4. **Test POST create:**
   ```bash
   curl -X POST "http://glue.eu.spryker.local/suppliers" \
     -H "Content-Type: application/json" \
     -d '{
       "data": {
         "type": "suppliers",
         "attributes": {
           "name": "New Supplier",
           "email": "new@supplier.com"
         }
       }
     }'
   ```
   Expected: 201 Created with new supplier data

5. **Test with search:**
   ```bash
   curl -X GET "http://glue.eu.spryker.local/suppliers?q=acme"
   ```
   Expected: Filtered results

### 🎓 Key Concepts to Remember

- API attributes use camelCase (JavaScript convention)
- Domain transfers use snake_case (PHP convention)
- Always map between API and domain transfers
- Resource providers handle routing and logic
- Use Client layer for business operations
- Return null for 404, throw exception for 500

---

## Exercise 11: Order Management System

**Branch:** `ilt/202512.0/intermediate/oms/skeleton`  
**Time:** 4 hours  
**Difficulty:** ⭐⭐⭐⭐⭐

### 🎯 Learning Objectives
- Define OMS state machines
- Create command plugins
- Create condition plugins
- Configure state transitions

### 📝 What You Need to Do

#### Task 1: Define State Machine

**File to create:** `config/Zed/oms/Demo01.xml`

**Your tasks:**
1. Define states: new, payment pending, invalid, payment authorized, paid, closed
2. Define transitions between states
3. Define events: authorize, pay
4. Add conditions to transitions
5. Mark happy path

**Hints:**
- States need: name, display (translation key)
- Reserved states: `reserved="true"` (affects inventory)
- Transitions need: source, target, event (optional), condition (optional)
- Happy path: `happy="true"`
- Events can be: manual, onEnter, timeout

**State machine structure:**
```xml
<process name="ProcessName" main="true">
    <states>
        <state name="state_name" reserved="true" display="oms.state.state-name"/>
    </states>

    <transitions>
        <transition happy="true" condition="Plugin/Name">
            <source>state_from</source>
            <target>state_to</target>
            <event>event_name</event>
        </transition>
    </transitions>

    <events>
        <event name="event_name" onEnter="true" manual="true" command="Plugin/Name"/>
    </events>
</process>
```

**Flow to implement:**
```
new --[authorize + IsAuthorized]--> payment pending
new --[authorize + !IsAuthorized]--> invalid
payment pending --[pay]--> payment authorized
payment authorized --[auto]--> paid
paid --[auto]--> closed
```

#### Task 2: Create Command Plugin

**File to create:** `src/SprykerAcademy/Zed/Oms/Communication/Plugin/Oms/Command/PayCommandPlugin.php`

**Your tasks:**
1. Implement CommandByOrderInterface
2. Implement run() method
3. Return empty array (demo implementation)

**Hints:**
- Method signature from interface: `run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)`
- Return type: `array`
- In real scenario: call payment gateway, log transaction, etc.

**Command pattern:**
```php
class SomeCommandPlugin extends AbstractCommand 
    implements CommandByOrderInterface
{
    public function run(
        array $orderItems, 
        SpySalesOrder $orderEntity, 
        ReadOnlyArrayObject $data
    ): array {
        // Execute business logic
        // Example: Call payment service
        // Example: Send notification
        
        return []; // Return array of errors if any
    }
}
```

#### Task 3: Create Condition Plugin

**File to create:** `src/SprykerAcademy/Zed/Oms/Communication/Plugin/Oms/Condition/IsAuthorizedConditionPlugin.php`

**Your tasks:**
1. Implement check() method from interface
2. Return true (demo implementation)

**Hints:**
- Method signature: `check(SpySalesOrderItem $orderItem): bool`
- In real scenario: check payment authorization status
- True = condition met (happy path)
- False = condition not met (alternative path)

**Condition pattern:**
```php
class SomeConditionPlugin extends AbstractCondition
{
    public function check(SpySalesOrderItem $orderItem): bool
    {
        // Check business condition
        // Example: Is payment authorized?
        // Example: Is inventory available?
        // Example: Is customer verified?
        
        return true; // or false
    }
}
```

#### Task 4: Register Plugins

**File to create:** `src/SprykerAcademy/Zed/Oms/OmsDependencyProvider.php`

**Your tasks:**
1. Extend Pyz\Zed\Oms\OmsDependencyProvider
2. Override extendCommandPlugins()
3. Override extendConditionPlugins()
4. Register your plugins with exact names matching XML

**Hints:**
- Call parent method first
- Use exact command/condition name from XML
- Name format: 'Demo/Pay', 'Demo/IsAuthorized'

**Registration pattern:**
```php
class OmsDependencyProvider extends PyzOmsDependencyProvider
{
    protected function extendCommandPlugins(Container $container): Container
    {
        $container = parent::extendCommandPlugins($container);

        $container->extend(self::COMMAND_PLUGINS, function (CommandCollectionInterface $collection) {
            $collection->add(new SomeCommandPlugin(), 'Plugin/Name');
            return $collection;
        });

        return $container;
    }

    protected function extendConditionPlugins(Container $container): Container
    {
        $container = parent::extendConditionPlugins($container);

        $container->extend(self::CONDITION_PLUGINS, function (ConditionCollectionInterface $collection) {
            $collection->add(new SomeConditionPlugin(), 'Plugin/Name');
            return $collection;
        });

        return $container;
    }
}
```

#### Task 5: Configure OMS

**File to modify:** `config/Shared/config_default.php`

**Your tasks:**
1. Set OMS process location
2. Add Demo01 to active processes

**Hints:**
- Process location: `config/Zed/oms`
- Use OmsConstants for keys

**Config structure:**
```php
use Spryker\Shared\Oms\OmsConstants;

$config[OmsConstants::PROCESS_LOCATION] = [
    APPLICATION_ROOT_DIR . '/config/Zed/oms',
];

$config[OmsConstants::ACTIVE_PROCESSES] = [
    'ProcessName',
];
```

### ✅ How to Test

1. **Verify state machine XML:**
   ```bash
   # Check XML syntax
   xmllint --noout config/Zed/oms/Demo01.xml
   ```

2. **Create test order:**
   - Go to Back Office → Sales → Create Order
   - Select Demo01 process
   - Complete order creation

3. **View state machine:**
   - Go to order detail page
   - Should see state machine diagram
   - Current state highlighted

4. **Trigger events:**
   - Click "Authorize" button (manual event)
   - Should transition to payment pending
   - Click "Pay" button
   - Should transition through states

5. **Test condition:**
   - Try changing IsAuthorizedConditionPlugin to return false
   - Order should go to invalid state instead

6. **Check console commands:**
   ```bash
   # Check for timeout events
   console oms:check-timeout

   # Check condition plugins
   console oms:check-condition
   ```

### 🎓 Key Concepts to Remember

- States represent order item status
- Transitions move between states
- Events trigger transitions (manual, automatic, timeout)
- Commands execute business logic
- Conditions determine which path to take
- Reserved states affect inventory
- Happy path is the ideal flow
- Plugin names in XML must match registration

---

## Completion Certificate 🎓

Congratulations on completing all 11 exercises! You now have comprehensive knowledge of Spryker backend development.

### Skills Acquired:
✅ Module architecture  
✅ Transfer Objects  
✅ Database schemas (Propel)  
✅ Back Office CRUD  
✅ Data Import  
✅ Publish & Synchronize  
✅ Elasticsearch integration  
✅ REST API development  
✅ OMS state machines  

### Next Steps:
- Review complete branches for best practices
- Build your own module from scratch
- Contribute to Spryker community
- Explore advanced topics (multi-store, multi-currency)

---

**For questions or support:**
- Spryker Documentation: https://docs.spryker.com
- Spryker Academy: https://academy.spryker.com
- Community Forum: https://discuss.spryker.com

