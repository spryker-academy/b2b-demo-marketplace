<?php

namespace Pyz\Zed\SupplierGui\Communication\Table;

use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SupplierTable extends AbstractTable
{
    public const COL_ID_ANTELOPE = 'id_supplier';

    public const COL_NAME = 'name';

    public const COL_COLOR = 'description';

    protected PyzSupplierQuery $supplierQuery;

    /**
     * @param \Orm\Zed\Supplier\Persistence\PyzSupplierQuery $supplierQuery
     */
    public function __construct(PyzSupplierQuery $supplierQuery)
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
            static::COL_ID_ANTELOPE => 'Supplier ID',
            static::COL_NAME => 'Name',
            static::COL_COLOR => 'Color',
        ]);

        $config->setSortable([
            static::COL_ID_ANTELOPE,
            static::COL_NAME,
            static::COL_COLOR,
        ]);

        $config->setSearchable([
            static::COL_NAME,
            static::COL_COLOR,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $supplierEntityCollection = $this->runQuery(
            $this->supplierQuery,
            $config,
            true,
        );

        if (!$supplierEntityCollection->count()) {
            return [];
        }

        return $this->mapReturns($supplierEntityCollection);
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
            $returns[] = $supplierEntity->toArray();
        }

        return $returns;
    }
}
