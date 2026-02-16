<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierLocation\Persistence;

use Orm\Zed\SupplierLocation\Persistence\PyzSupplierLocationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerAcademy\Zed\SupplierLocation\Persistence\Propel\Mapper\SupplierLocationMapper;

class SupplierLocationPersistenceFactory extends AbstractPersistenceFactory
{
    public function createSupplierLocationQuery(): PyzSupplierLocationQuery
    {
        return PyzSupplierLocationQuery::create();
    }

    public function createSupplierLocationMapper(): SupplierLocationMapper
    {
        return new SupplierLocationMapper();
    }
}
