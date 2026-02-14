<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication\Table;

use Orm\Zed\Supplier\Persistence\Map\PyzSupplierTableMap;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SupplierTable extends AbstractTable
{
    public const string COL_ID_SUPPLIER = PyzSupplierTableMap::COL_ID_SUPPLIER;

    public const string COL_NAME = PyzSupplierTableMap::COL_NAME;

    public const string COL_DESCRIPTION = PyzSupplierTableMap::COL_DESCRIPTION;

    public const string COL_STATUS = PyzSupplierTableMap::COL_STATUS;

    public const string COL_EMAIL = PyzSupplierTableMap::COL_EMAIL;

    public const string COL_PHONE = PyzSupplierTableMap::COL_PHONE;

    /**
     * @param \Orm\Zed\Supplier\Persistence\PyzSupplierQuery $supplierQuery
     */
    public function __construct(protected PyzSupplierQuery $supplierQuery)
    {
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    #[\Override]
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        // Info: Have a look inside the class TableConfiguration for the right setters

        // TODO-1: Set the table header for id, name, description, status, email and phone by passing an associative array of columns
        // Hint-1: As array keys you can use the constants of the current class
        // Hint-2: The values are the column names of the table visible in the browser

        // TODO-2: Make the columns for id, name, description, status, email and phone sortable
        // Hint-1: Pass the keys of the columns that should be sortable

        // TODO-3: Make the columns for name, description, email and phone searchable
        // Hint-1: Pass the keys of the columns that should be searchable

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    #[\Override]
    protected function prepareData(TableConfiguration $config): array
    {
        // TODO-4: Fetch an $supplierEntityCollection and return it in an array format
        // Hint-1: You can use the `runQuery()`-method from the parent class to fetch a collection of supplier entities
        // Hint-2: Third parameter of runQuery should be set to true
        // Hint-3: You are allowed to use the `mapReturns()`-method
        $supplierEntityCollection = null;

        return [];
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Supplier\Persistence\PyzSupplier> $supplierEntityCollection
     *
     * @return array
     */
    protected function mapReturns(ObjectCollection $supplierEntityCollection): array
    {
        $returns = [];

        foreach ($supplierEntityCollection as $supplierEntity) {
            $returns[] = [
                static::COL_ID_SUPPLIER => $supplierEntity->getIdSupplier(),
                static::COL_NAME => $supplierEntity->getName(),
                static::COL_DESCRIPTION => $supplierEntity->getDescription(),
                static::COL_STATUS => $supplierEntity->getStatus(),
                static::COL_EMAIL => $supplierEntity->getEmail(),
                static::COL_PHONE => $supplierEntity->getPhone(),
            ];
        }

        return $returns;
    }
}
