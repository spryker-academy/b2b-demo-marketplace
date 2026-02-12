<?php

namespace Pyz\Zed\Supplier\Persistence;

use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Pyz\Zed\Supplier\Persistence\Propel\Mapper\SupplierMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class SupplierPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Supplier\Persistence\PyzSupplierQuery
     */
    public function createSupplierQuery(): PyzSupplierQuery
    {
        return PyzSupplierQuery::create();
    }

    /**
     * @return \Pyz\Zed\Supplier\Persistence\Propel\Mapper\SupplierMapper
     */
    public function createSupplierMapper(): SupplierMapper
    {
        return new SupplierMapper();
    }
}
