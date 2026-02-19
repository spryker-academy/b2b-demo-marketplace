<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Business;

use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface;
use SprykerAcademy\Zed\SupplierStorage\Business\Writer\SupplierStorageWriter;
use SprykerAcademy\Zed\SupplierStorage\SupplierStorageDependencyProvider;

/**
 * @method \SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageRepositoryInterface getRepository()
 * @method \SprykerAcademy\Zed\SupplierStorage\Persistence\SupplierStorageEntityManagerInterface getEntityManager()
 * @method \SprykerAcademy\Zed\SupplierStorage\SupplierStorageDependencyProvider getDependencyProvider()
 */
class SupplierStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerAcademy\Zed\SupplierStorage\Business\Writer\SupplierStorageWriter
     */
    public function createSupplierStorageWriter(): SupplierStorageWriter
    {
        return new SupplierStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getSupplierFacade(),
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): EventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(SupplierStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface
     */
    public function getSupplierFacade(): SupplierFacadeInterface
    {
        return $this->getProvidedDependency(SupplierStorageDependencyProvider::FACADE_SUPPLIER);
    }
}
