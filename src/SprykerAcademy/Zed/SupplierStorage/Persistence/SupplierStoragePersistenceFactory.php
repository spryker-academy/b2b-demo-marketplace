<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Persistence;

use Orm\Zed\SupplierStorage\Persistence\PyzSupplierStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerAcademy\Zed\SupplierStorage\Persistence\Propel\Mapper\SupplierStorageMapper;

/**
 * @method \SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageRepositoryInterface getRepository()
 * @method \SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageEntityManagerInterface getEntityManager()
 */
class SupplierStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SupplierStorage\Persistence\PyzSupplierStorageQuery
     */
    public function createSupplierStorageQuery(): PyzSupplierStorageQuery
    {
        return PyzSupplierStorageQuery::create();
    }

    /**
     * @return \SprykerAcademy\Zed\SupplierStorage\Persistence\Propel\Mapper\SupplierStorageMapper
     */
    public function createSupplierStorageMapper(): SupplierStorageMapper
    {
        return new SupplierStorageMapper();
    }
}
