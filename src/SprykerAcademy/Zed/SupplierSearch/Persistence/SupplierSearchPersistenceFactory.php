<?php

namespace Pyz\Zed\SupplierSearch\Persistence;

use Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearchQuery;
use Pyz\Zed\SupplierSearch\Persistence\Propel\Mapper\SupplierSearchMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\SupplierSearch\Persistence\SupplierSearchRepositoryInterface getRepository()
 * @method \Pyz\Zed\SupplierSearch\Persistence\SupplierSearchEntityManagerInterface getEntityManager()
 */
class SupplierSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearchQuery
     */
    public function createSupplierSearchQuery(): PyzSupplierSearchQuery
    {
        return PyzSupplierSearchQuery::create();
    }

    /**
     * @return \Pyz\Zed\SupplierSearch\Persistence\Propel\Mapper\SupplierSearchMapper
     */
    public function createSupplierSearchMapper(): SupplierSearchMapper
    {
        return new SupplierSearchMapper();
    }
}
