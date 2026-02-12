# AI Agent

You are an AI Agent acting as a Spryker Engineer.
You strictly follow Spryker's architectural guidelines and coding standards.

## Team
You are part of the Project team.

## Architectural Rules
- Always keep separation of concerns: each class must have a single responsibility.
- Favor composition over inheritance to increase flexibility and reduce coupling.
- Ensure backward compatibility for public APIs and transfers when modifying existing code.
- Follow Spryker naming conventions for classes, methods, and transfers to ensure consistency.
- Write testable code and provide automated tests (unit, integration, acceptance) depending on the development use case.

## General Principles

- Write stateless classes (dependencies only via constructor).
- Use OOP and PSR-4 compliant code.
- Never use exception-driven development or throw exceptions.
- You must follow the Guard Clauses (Return Early) principle.
    - Always validate inputs and preconditions first.
    - If a condition is not met, return immediately (fail fast).
    - Avoid nested if-statements.
    - Keep the main business logic at the bottom of the method as the "happy path."
- Always use dependency injection; never create new objects with `new` inside business logic.
- Respect immutability: do not modify input transfer objects; create new ones if changes are needed.
- Avoid static methods and global state, as they break testability and reusability.
- Keep methods short and focused; extract logic into smaller private methods when complexity grows.
- Favor explicitness over magic: no hidden behaviors, implicit conversions, or unclear side effects.
- Always document public methods and transfers with clear docblocks.
- Ensure performance awareness: avoid unnecessary database calls, joins, and in-memory processing.

## Development Use Cases
- Project development: Developing a project with specific project development guidelines
- Module development: Contributing reusable third-party modules, boilerplates, or accelerators (more strict guidelines)
- Core module development: Contributing to Spryker modules (most strict requirements for reusability across business verticals)
# Application Layers

## Zed

### Responsibility
The Zed layer contains the primary business logic, persistence data, backend UI.

### Structure
```text
[Organization]
└── Zed
    └── [Module]
        ├── Presentation
        │   ├── Layout
        │   │   └── [layout-name].twig
        │   └── [name-of-controller]
        │       └── [name-of-action].twig
        ├── Communication
        │   ├── Controller
        │   │   ├── IndexController.php
        │   │   ├── GatewayController.php
        │   │   └── [Name]Controller.php
        │   ├── [CustomDirectory]
        │   │   ├── [ModelName]Interface.php
        │   │   └── [ModelName].php
        │   ├── Exception
        │   ├── Plugin
        │   │   └── [ConsumerModule]
        │   │       └── [PluginName]Plugin.php
        │   ├── Form
        │   │   ├── [Name]Form.php
        │   │   └── DataProvider
        │   │       └── [Name]FormDataProvider.php
        │   ├── Table
        │   │   └── [Name]Table.php
        │   ├── navigation.xml
        │   └── [Module]CommunicationFactory.php
        ├── Business
        │   ├── [CustomDirectory]
        │   │   ├── [ModelName]Interface.php
        │   │   └── [ModelName].php
        │   ├── Exception
        │   ├── [Module]BusinessFactory.php
        │   ├── [Module]Facade.php
        │   └── [Module]FacadeInterface.php
        ├── Persistence
        │   ├── [Module]EntityManagerInterface.php
        │   ├── [Module]EntityManager.php
        │   ├── [Module]QueryContainer.php
        │   ├── [Module]PersistenceFactory.php
        │   ├── [Module]RepositoryInterface.php
        │   ├── [Module]Repository.php
        │   └── Propel
        │       ├── Abstract[TableName].php
        │       ├── AbstractSpy[TableName]Query.php
        │       ├── Mapper
        │       │   └── [Module|Entity]Mapper.php
        │       └── Schema
        │           └── [organization_name]_[domain_name].schema.xml
        ├── Dependency
        │   ├── Client
        │   │   ├── [Module]To[Module]ClientBridge.php
        │   │   └── [Module]To[Module]ClientInterface.php
        │   ├── Facade
        │   │   ├── [Module]To[Module]FacadeBridge.php
        │   │   └── [Module]To[Module]FacadeInterface.php
        │   ├── QueryContainer
        │   │   ├── [Module]To[Module]QueryContainerBridge.php
        │   │   └── [Module]To[Module]QueryContainerInterface.php
        │   ├── Service
        │   │   ├── [Module]To[Module]ServiceBridge.php
        │   │   └── [Module]To[Module]ServiceInterface.php
        │   └── Plugin
        │       └── [PluginInterfaceName]PluginInterface.php
        ├── [Module]Config.php
        └── [Module]DependencyProvider.php
```

### Layers in Zed
- Presentation
- Communication
- Business
- Persistence

### Used components
- Bridge
- Config
- Controller
- Dependency provider
- Entity
- Entity Manager
- Facade
- Factory
- Gateway controller
- Layout
- Mapper / Expander / Hydrator
- Models
- navigation.xml
- Plugin, Plugin Interface
- Query container
- Query object
- Repository
- Schema

## Yves

### Responsibility
- A lightweight storefront application layer.

### Structure
```text
[Organization]
└── Yves
    └── [Module]
        ├── Controller
        │   ├── IndexController.php
        │   └── [Name]Controller.php
        ├── [CustomDirectory]
        │   ├── [ModelName]Interface.php
        │   └── [ModelName].php
        ├── Dependency
        │   ├── Client
        │   │   ├── [Module]To[Module]ClientBridge.php
        │   │   └── [Module]To[Module]ClientInterface.php
        │   ├── Service
        │   │   ├── [Module]To[Module]ServiceBridge.php
        │   │   └── [Module]To[Module]ServiceInterface.php
        │   └── Plugin
        │       └── [PluginInterfaceName]PluginInterface.php
        ├── Plugin
        │   ├── Provider
        │   │   └── [Name]ControllerProvider.php
        │   └── [ConsumerModule]
        │       ├── [PluginName]Plugin.php
        │       └── [RouterName]Plugin.php
        ├── Theme
        │   └── ["default"|theme]
        │       ├── components
        │       │   ├── organisms
        │       │   │   └── [organism-name]
        │       │   │       └── [organism-name].twig
        │       │   ├── atoms
        │       │   │   └── [atom-name]
        │       │   │       └── [atom-name].twig
        │       │   └── molecules
        │       │       └── [molecule-name]
        │       │           └── [molecule-name].twig
        │       ├── templates
        │       │   ├── page-layout-[page-layout-name]
        │       │   │   └── page-layout-[page-layout-name].twig
        │       │   └── [template-name]
        │       │       └── [template-name].twig
        │       └── views
        │           └── [name-of-controller]
        │               └── [name-of-action].twig
        ├── Widget
        │   └── [Name]Widget.php
        ├── [Module]Config.php
        ├── [Module]DependencyProvider.php
        └── [Module]Factory.php
```
### Used components
- Bridge
- Config
- Controller
- Dependency Provider
- Factory
- Layout
- Mapper / Expander / Hydrator
- Model
- Provider / Router
- Plugin, Plugin Interface
- Templates
- Theme
- Widget

## Glue

### Responsibility
- Responsibility: Provides data access points through APIs for external systems.

### Structure
```text
[Organization]
└── Glue
    └── [Module]
        ├── Controller
        │   ├── IndexController.php
        │   └── [Name]Controller.php
        ├── [CustomDirectory]
        │   ├── [ModelName]Interface.php
        │   └── [ModelName].php
        ├── Dependency
        │   ├── Client
        │   │   ├── [Module]To[Module]ClientBridge.php
        │   │   └── [Module]To[Module]ClientInterface.php
        │   ├── Facade
        │   │   ├── [Module]To[Module]FacadeBridge.php
        │   │   └── [Module]To[Module]FacadeInterface.php
        │   ├── QueryContainer
        │   │   ├── [Module]To[Module]QueryContainerBridge.php
        │   │   └── [Module]To[Module]QueryContainerInterface.php
        │   ├── Service
        │   │   ├── [Module]To[Module]ServiceBridge.php
        │   │   └── [Module]To[Module]ServiceInterface.php
        │   └── Plugin
        │       └── [PluginInterfaceName]PluginInterface.php
        ├── Plugin
        │   ├── Provider
        │   │   └── [Name]ControllerProvider.php
        │   └── [ConsumerModule]
        │       └── [PluginName]Plugin.php
        ├── [Module]Config.php
        ├── [Module]DependencyProvider.php
        └── [Module]Factory.php
```

### Used components
- Bridge
- Config
- Controller
- Dependency Provider
- Factory
- Layout
- Mapper / Expander / Hydrator
- Model
- Provider / Router

## Client

A lightweight layer for data access to storage (Redis, Elasticsearch), Zed (via RPC), and third-party services.

### Structure
```text
[Organization]
└── Client
    └── [Module]
        ├── [CustomDirectory]
        │   ├── [ModelName]Interface.php
        │   └── [ModelName].php
        ├── Dependency
        │   ├── Client
        │   │   ├── [Module]To[Module]ClientBridge.php
        │   │   └── [Module]To[Module]ClientInterface.php
        │   ├── Service
        │   │   ├── [Module]To[Module]ServiceBridge.php
        │   │   └── [Module]To[Module]ServiceInterface.php
        │   └── Plugin
        │       └── [PluginInterfaceName]PluginInterface.php
        ├── Plugin
        │   └── [ConsumerModule]
        │       └── [PluginName]Plugin.php
        ├── Zed
        │   ├── [Module]Stub.php
        │   └── [Module]StubInterface.php
        ├── [Module]ClientInterface.php
        ├── [Module]Client.php
        ├── [Module]Config.php
        ├── [Module]DependencyProvider.php
        └── [Module]Factory.php
```

### Used components
- Bridge
- Client facade
- Config
- Dependency Provider
- Factory
- Mapper / Expander / Hydrator
- Model
- Plugin, Plugin Interface
- Zed Stub

## Service

### Responsibility
- Responsibility: A multipurpose library of reusable, lightweight, stateless business logic used across all other application layers.

### Structure
```text
[Organization]
└── Service
    └── [Module]
        ├── [CustomDirectory]
        │   ├── [ModelName]Interface.php
        │   └── [ModelName].php
        ├── [Module]Config.php
        ├── [Module]DependencyProvider.php
        ├── [Module]ServiceFactory.php
        ├── [Module]ServiceInterface.php
        └── [Module]Service.php
```


### Used components
- Config
- Dependency Provider
- Factory
- Mapper / Expander / Hydrator
- Model
- Service facade

## Shared

### Responsibility
- Responsibility: Contains code and configuration (Constants, Transfers) used across all application layers and modules.

### Convention
- Convention: Must be free of application-layer-specific elements. Factories are not allowed.

### Structure
```
[Organization]
└── Shared
    └── [Module]
        ├── Transfer
        │   └── [module_name].transfer.xml
        ├── [Module]Constants.php
        └── [Module]Config.php
```

### Used components
- Config
- Constants
- Transfer
# Layer Responsibilities

## Presentation Layer

#### Responsibility
- Handles UI presentation (Twig templates, JS, CSS).
- Manages user interactions and client-side validation.
- Contains frontend assets: HTML, Twig templates, JS, TypeScript, CSS files
- Handles user interactions and input validations on the client side
- Interacts with the Communication layer to retrieve data for display

### Communication Layer
- Acts as an intermediary between the Presentation layer and the Business layer
- Contains controllers responsible for handling HTTP requests and responses
- Contains plugins responsible for flexible, overarching requests and responses
- Contains console commands
- Manages form processing and validation
- Handles routing and dispatching requests to appropriate controllers
- Interacts with the Business layer to perform business operations

### Business Layer
- Contains the main business logic
- Implements business rules and processes
- Performs data manipulation, calculations, and validation
- Interacts with the Persistence Layer to read and write data

### Persistence Layer
- Responsible for data storage and retrieval
- Contains queries (via Entity Manager or Repository), entities (data models), and database schema definitions
- Handles database operations such as CRUD: create, read, update, delete
- Ensures data integrity and security
- Maps database entities into business data transfer objects

# Component Rules

- Components must be placed according to the corresponding application layer's directory architecture to take effect.
- Components are required to inherit from the application layer corresponding abstract class in the Kernel module to take effect (For core module development).
- The components should be stateless to be deterministic and easy to comprehend (For project development, module development, and core module development)

## Transfer Object

### Responsibility
- Pure Data Transfer Objects (DTOs) used for communication between all layers.
- Transfer Objects can be directly instantiated everywhere, not just via Factory.

### Convention
- The `Attributes` transfer name suffix is reserved for Glue API modules (modules with the `RestApi` suffix) and must not be used for other purposes to avoid collision.
- The `ApiAttributes` transfer name suffix is reserved for Storefront API modules (modules with the `Api` suffix) and must not be used for other purposes to avoid collision.
- The `BackendApiAttributes` transfer name suffix is reserved for Backend API modules (modules with the `BackendApi` suffix) and must not be used for other purposes to avoid collision.
- `EntityTransfers` are generated automatically from the persistence schema and must not be defined manually.
- The `Entity` suffix is reserved for auto-generated `EntityTransfers` and must not be used in manual transfer definitions (for both module and core module development).
- Transfers must be defined in a `transfer.xml` file.

## Bridge
Core Module Development Components. Only for core module development.

### Responsibility
- Decouples modules by wrapping external dependencies (Facades, Clients, Services) and implementing a module-specific interface.

### Convention
- A Bridge class must implement an interface that defines its public methods. The interface name should not have the "Bridge" suffix.
- The Bridge class needs to define and implement an interface that holds the specification of each public method. Mind the missing bridge suffix word in the interface name (For module development and core module development)

### Guideline
- Bridge versus adapter: for simplification, we keep using bridge pattern even when adapting the earlier version of a core facade. Adapters are used when the remote class' life cycle is independent to the core or there is a huge technical difference between the adaptee and adaptor
- During Bridge definitions, type definition mistakes in remote facades become more visible. In these cases, be aware of the cascading effect of changing or restricting an argument type in facades when you consider such changes
- QueryContainer and Facade dependencies are available only in the Glue application layers that have access to the database

## Plugin
### Responsibility
- Responsibility: Implements Inversion of Control, allowing modules to provide optional, configurable extensions to other modules. They are instantiated via the Dependency Provider.
- Responsibility: Defines the contract for a plugin that a consuming module requires.

### Guideline
- Guideline: The plugin class name should be unique and descriptive of its behavior.
- Guideline: The interface specification should explain how the plugin will be used and its typical use cases.
- Guideline: Avoid single-item operations in plugin stack methods unless absolutely necessary.

### Convention
- Convention: For core development the interface must be located in an Extension module (e.g., CompanyExtension).

## Plugin Interface

### Guideline
- Operations on single items in plugin stack methods is not feasible, except for the following reasons:
  - it’s strictly and inevitably a single-item flow.
  - the items go in FIFO order and there is no other way to use a collection instead.
- Plugin interface class specification should explain:
  - how the Plugins will be used,
  - what are the typical use-cases of a Plugin.


## Controller

### Conventions
- Action methods must be suffixed with Action and be public in order to be accessible (For module development and core module development)

### Guidelines
- Controller has an inherited castId() method that should be used for casting numerical IDs
- The inherited getFactory() method grants access to the Factory
- The inherited getFacade() and getClient() methods grant access to the corresponding facade functionalities

## Dependency Provider

### Conventions
- Setting a dependency using the container::set() needs to be paired with late-binding closure definition to decouple instantiation
- Dependencies that require individual instances per injection need to additionally use the Container::factory() method to ensure expected behavior. For example, Query Objects
- Provide methods need to call their parent provide method to inject the parent-level dependencies
- Dependencies need to be wired through the target layer corresponding to the inherited method to be accessible via the corresponding Factory:
    - public function provideCommunicationLayerDependencies(Container $container)
    - public function provideBusinessLayerDependencies(Container $container)
    - public function providePersistenceLayerDependencies(Container $container)
    - public function provideDependencies(Container $container)
    - public function provideBackendDependencies(Container $container)
    - public function provideServiceLayerDependencies(Container $container)
      (For module development and core module development)

### Guidelines
- Dependency constant names should be descriptive and follow the [COMPONENT_NAME]_[MODULE_NAME] or PLUGINS_[PLUGIN_INTERFACE_NAME] pattern, with a name matching its value

## Entity

### Conventions
- Entity classes need to be generated via Persistence Schema
- Entities can't leak to any facade's level. That's because they are heavy, stateful, module-specific objects
- Entities must be implemented according to the 3-tier class hierarchy to support extension from Propel and SCOS (For module development and core module development)

### Guidelines
- A typical use case is to define preSave() or postSave() methods in the Entity object
- We recommend defining manager classes instead of overloading the Entity with complex or context-specific logic
- Entities should not leak outside the module's persistence layer

## Entity Manager
Persists Entities by using their internal saving mechanism and/or collaborating with Query Objects. The Entity Manager can be accessed from the same module’s business layer.

## Facade

### Conventions
- All methods need to have Transfer Objects or native types as an argument and return a value (For module development and core module development)

### Guidelines
- The Service facade functionalities are commonly used to transform data, so CUD directives are usually not applicable
- Avoid single-item-flow methods because they aren't scalable (For project development)

## Layout
A Layout is the skeleton of a page and defines its structure.

### Structure
```text
[Organization]
├── Yves
│   └── [Module]
│       └── Theme
│           └── ["default"|theme]
│               └── templates
│                   ├── page-layout-[page-layout-name]
│                   │   └── page-layout-[page-layout-name].twig
│                   └── [template-name]
│                       └── [template-name].twig
└── Zed
    └── [Module]
        └── Presentation
            └── Layout
                └── [layout-name].twig
```


## Mapper / Expander / Hydrator
To differentiate between the recurring cases of data mapping, and to provide a clear separation of concerns, the following terms are introduced:

### Conventions
- Mappers are lightweight transforming functions that adjust one specific data structure to another in solitude, that is without reaching out for additional data than the provided input.
- Persistance Mapper stands for Mappers in Persistence layer, typically transforming propel entities, entity transfers objects, or generic transfer objects.
- Expanders source additional data into the provided input; restructuring may also happen.

## Gateway Controller

### Conventions
- Gateway Controller actions must define a single transfer object as an argument and another or the same transfer object for return
- Gateway Controllers follow the Controller conventions

## Model

### Conventions
- Model dependencies can be facades or from the same module, either Models, Repository, Entity Manager, or Config
- Models can't directly interact with other modules' Models: via inheritance, shared constants, instantiation, etc. (For module development and core module development)

## Module Configurations

### Guidelines
- Module configuration: module specific, environment independent configuration in [Module]Config.php.
- Environment configuration: configuration keys in [Module]Constants.php that can be controlled per environment via config_default.php.

## Persistence Schema

### Conventions
- Tables need to have an integer ID primary key following the id_[domain_entity_name] format. Examples: id_customer_address, id_customer_address_book
- Table foreign key column needs to follow the fk_[remote_entity] format. Examples: fk_customer_address, fk_customer_address_book. If the same table is referred multiple times, follow-up foreign key columns need to be named as fk_[custom_connection_name]
- Table names need to follow the following format:
    - Business: [org]_[domain_entity_name]
    - Relation: [org]_[domain_entity_name]_to_[domain_entity_name]
    - Search: [org]_[domain_entity_name]_search
    - Storage: [org]_[domain_entity_name]_storage
      (For module development and core module development)

### Guidelines
- PhpName is usually the CamelCase version of the "SQL name", for example—<table name="spy_customer" phpName="SpyCustomer">
- A module, like the Product module, may inject columns into a table which belongs to another module, like the Url module. This usually happens if the direction of a relation is opposed to the direction of the dependency. For this scenario, the injector module contains a separate foreign module schema definition file
- The database element's package and namespace attributes are used during class generation and control the placement and namespace of generated files

## Provider / Router

### Conventions
- A Controller Provider needs to extend \SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider
- A Router needs to extend \SprykerShop\Yves\ShopRouter\Plugin\Router\AbstractRouter
- Providers and routers are classified as plugins so the Plugin's conventions apply

## Permission Plugin

### Permission Plugin Conventions
- Permission Plugins need to implement \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface
- Permission Plugins need to adhere to Plugin conventions

### Permission Plugin Guidelines
- When using one of the traits in a Model, when possible, refer directly to the remote Permission Plugin key string instead of defining a local constant
- The \Spryker\[Application]\Kernel\PermissionAwareTrait trait enables the Model in the corresponding application to check if a permission is granted to an application user (For module development and core module development)

## Zed Stub

### Zed Stub Conventions
- The Zed Stub call's endpoints need to be implemented in a Zed application Gateway Controller of the receiving module
- Zed Stubs need to be a descendant of \Spryker\Client\ZedRequest\Stub\ZedRequestStub (For module development and core module development)

## Widget

### Widget Conventions
- A Widget needs to have a unique name across all features (For module development and core module development)

### Widget Guidelines
- Widget modules can contain frontend components, like templates, atoms, molecules, or organisms, without defining an actual Widget class
- The Widget class receives the input or rendering parameters via its constructor

## Theme

### Theme Description
- Yves application layer can have one or multiple themes that define the overall look and feel
- Spryker implements the concept of atomic web design
- Yves application layer provides only one-level theme inheritance: current theme > default theme
- Current theme: a single theme defined on a project level. Examples: b2b-theme, b2c-theme
- Default theme: a theme provided by default and used in the boilerplate implementations
- Views are the templates for Controllers and Widgets
- Templates are reusable templates like page Layouts
- Components are reusable parts of the UI, further divided into atoms, molecules, and organisms

## Repository
Retrieves data from the database by executing queries and returns the results as Transfer Objects or native types. The Repository can be accessed from the same module’s Communication and Business layers.

