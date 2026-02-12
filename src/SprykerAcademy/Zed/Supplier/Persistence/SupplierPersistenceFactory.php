<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\Supplier\Persistence;

use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerAcademy\Zed\Supplier\Persistence\Propel\Mapper\SupplierMapper;

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
     * @return \SprykerAcademy\Zed\Supplier\Persistence\Propel\Mapper\SupplierMapper
     */
    public function createSupplierMapper(): SupplierMapper
    {
        return new SupplierMapper();
    }
}
