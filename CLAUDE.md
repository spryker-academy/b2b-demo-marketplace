# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

This is a **Spryker B2B Marketplace Demo Shop** used as the foundation for Spryker Backend Developer Training (ILT). Exercise code lives in the `SprykerAcademy` namespace under `src/SprykerAcademy/` and `tests/SprykerAcademyTest/`. The `Pyz` namespace is the standard project-level customization layer.

All services run inside Docker via Spryker's SDK (`docker/sdk`). PHP must be run via `docker/sdk cli` or `docker/sdk console`.

---

## Common Commands

### Docker Environment
```bash
docker/sdk boot deploy.dev.yml     # Bootstrap dev environment
docker/sdk up                      # Start all services
docker/sdk cli                     # Open shell inside container
docker/sdk trouble                 # Tear down environment (troubleshooting)
```

### Code Generation (run after modifying XML files)
```bash
docker/sdk console transfer:generate          # After changing .transfer.xml files
docker/sdk console propel:install             # After changing .schema.xml files
docker/sdk cli composer dump-autoload         # After adding new classes/namespaces
```

### Cache
```bash
docker/sdk console c:e                        # Empty all caches (alias for cache:empty-all)
docker/sdk console router:cache:warm-up       # After adding new route providers
docker/sdk console navigation:build-cache     # After modifying navigation XML
docker/sdk console glue-api:controller:cache:warm-up  # After adding Glue API resources
```

### Data
```bash
docker/sdk console data:import                # Run data importers
docker/sdk console event:trigger              # Trigger publish & sync events
docker/sdk console queue:worker:start         # Process queued messages
docker/sdk console search:setup:sources       # After modifying search schemas
```

### Running Tests
```bash
# Run a specific exercise test suite
vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/ContactRequest/ Exercise4

# Run all SprykerAcademy tests
vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/ContactRequest/

# Run standard Pyz tests
vendor/bin/codecept run -c codeception.yml
```

### Static Analysis & Linting
```bash
vendor/bin/phpstan analyse                    # PHPStan (level 6)
vendor/bin/phpcs --standard=phpcs.xml src/    # Code sniffer
vendor/bin/phpcbf --standard=phpcs.xml src/  # Auto-fix code style
```

---

## Architecture

### Namespace Structure
- **`Pyz`** — Project-level customizations extending Spryker core (`src/Pyz/`)
- **`SprykerAcademy`** — Training exercises namespace (`src/SprykerAcademy/`)
- **`SprykerAcademyTest`** — Exercise tests (`tests/SprykerAcademyTest/`)

To register a new namespace: add to `composer.json` `autoload.psr-4` AND to `$config[KernelConstants::PROJECT_NAMESPACES]` in `config/Shared/config_default.php`.

### Spryker Module Layer Pattern
Each module is split across application layers:

| Layer | Location | Responsibility |
|-------|----------|----------------|
| **Zed/Business** | `Facade`, `Reader`, `Writer` | Business logic; external API via `Facade` |
| **Zed/Persistence** | `Repository`, `EntityManager`, `Mapper` | DB reads/writes via Propel ORM |
| **Zed/Communication** | `Controller` (including `GatewayController`) | HTTP controllers and Zed-to-Yves RPC |
| **Client** | `Client`, `Stub`, `Factory` | Yves-to-Zed communication via ZedStub |
| **Yves** | `Controller`, `Plugin/Router`, `Theme/` | Storefront rendering (Twig) |
| **Shared** | Transfer XML, constants | Shared transfer objects across layers |

### Transfer Objects
- Defined in `src/*/Shared/*/Transfer/*.transfer.xml`
- Generated PHP classes appear in `src/Generated/Shared/Transfer/`
- Always use generated Transfers to pass data between layers — never plain arrays

### Propel ORM
- Schema defined in `src/*/Zed/*/Persistence/Propel/Schema/*.schema.xml`
- Generated entity classes appear in `src/Orm/`
- Run `docker/sdk console propel:install` after schema changes

### Routing (Yves)
- Route provider plugins implement `AbstractRouteProviderPlugin`
- Must be registered in `src/Pyz/Yves/Router/RouterDependencyProvider.php`

### ZedStub / Client-to-Zed RPC
- Client calls Zed via a `*Stub` class that routes to a `GatewayController` action
- `GatewayController` extends `AbstractGatewayController` and calls `getFacade()`

---

## Exercise Workflow

Load exercises using:
```bash
./exercises/load.sh <package> <branch>
# e.g.: ./exercises/load.sh contact-request basics/module-layers/skeleton
```

After loading, always run:
```bash
docker/sdk console c:e
docker/sdk cli composer dump-autoload
docker/sdk console transfer:generate
docker/sdk console propel:install
```

Exercise tests for the current module are in `tests/SprykerAcademyTest/Zed/ContactRequest/` organized by `Exercise1`–`Exercise5` suites.

---

## Code Standards

- PHP 8.3+, strict types required (`declare(strict_types=1)`)
- Spryker coding standard enforced via `phpcs.xml` (extends `SprykerStrict` ruleset)
- Trailing commas required in function declarations and closure uses
- No `==` equality — use `===` only
- No superglobal variables
- PHPStan level 6; generated code (`src/Generated/`, `src/Orm/`) is excluded
