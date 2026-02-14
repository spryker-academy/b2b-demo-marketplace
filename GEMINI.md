## Project Overview

This project is a Spryker B2B Marketplace Demo Shop. It is a PHP-based e-commerce application built on the Spryker framework. The project uses Docker for its development environment and includes a substantial frontend component built with Node.js, Angular, and Webpack.

**Key Technologies:**

*   **Backend:** PHP, Spryker
*   **Frontend:** Node.js, Angular, Webpack, SCSS, TypeScript
*   **Database:** PostgreSQL/MariaDB (configurable)
*   **Search:** Elasticsearch
*   **Environment:** Docker

**Architecture:**

The application is divided into several layers:

*   **Yves:** The frontend application (customer-facing).
*   **Zed:** The backend application (back-office and business logic).
*   **Glue:** The REST API layer.
*   **Merchant Portal:** A separate frontend application for marketplace merchants.

## Building and Running

The project is designed to be run with Docker. The primary commands are managed through the `docker/sdk` script.

**Initial Setup:**

1.  Clone the repository and the `docker-sdk`:
    ```bash
    git clone https://github.com/spryker-shop/b2b-demo-marketplace.git ./spryker-b2b-marketplace
    git clone git@github.com:spryker/docker-sdk.git docker
    ```
2.  Bootstrap the development environment:
    ```bash
    docker/sdk boot deploy.dev.yml
    ```
3.  Build and start the application:
    ```bash
    docker/sdk up
    ```

**Frontend Development:**

The frontend assets are managed with npm/yarn and Webpack.

*   **Build Yves (customer frontend):**
    ```bash
    npm run yves
    ```
*   **Watch Yves for changes:**
    ```bash
    npm run yves:watch
    ```
*   **Build Zed (backend UI):**
    ```bash
    npm run zed
    ```
*   **Watch Zed for changes:**
    ```bash
    npm run zed:watch
    ```
*   **Build Merchant Portal:**
    ```bash
    npm run mp:build
    ```
*   **Watch Merchant Portal for changes:**
    ```bash
    npm run mp:build:watch
    ```

**Testing:**

The project uses Codeception for backend testing and Jest/Cypress for frontend testing.

*   **Run backend tests:**
    ```bash
    vendor/bin/codecept run
    ```
*   **Run Merchant Portal tests:**
    ```bash
    npm run mp:test
    ```

## Development Conventions

*   **Coding Style:**
    *   PHP code should adhere to the rules defined in `phpcs.xml`. Use `vendor/bin/phpcs` to check for violations.
    *   Frontend code (TypeScript, SCSS) should follow the guidelines enforced by ESLint (`.eslintrc.json`) and Stylelint (`stylelintrc.js`).
*   **Static Analysis:**
    *   PHP code is analyzed with PHPStan. The configuration is in `phpstan.neon`. Run it with `npm run phpstan`.
*   **Commits:** Follow conventional commit standards.
*   **Branching:** Use feature branches for new development.
*   **Contributing:** For more detailed contribution guidelines, refer to the [official Spryker documentation](https://docs.spryker.com/docs/dg/dev/code-contribution-guide.html).
