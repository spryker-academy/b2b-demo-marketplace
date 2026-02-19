<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerAcademy\Zed\SupplierStorage\Business\Writer\SupplierStorageWriter;

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
        // TODO-3: Provide all dependencies to the SupplierStorageWriter constructor.
        // Hint-1: getEventBehaviorFacade(), getSupplierFacade(), getRepository(), getEntityManager().
        return new SupplierStorageWriter();
    }

    // TODO-1: Create getEventBehaviorFacade() and return EventBehaviorFacade.
    // Hint-1: Use $this->getProvidedDependency() with the constant from DependencyProvider.

    // TODO-2: Create getSupplierFacade() and return SupplierFacade.
    // Hint-1: Use $this->getProvidedDependency() with the constant from DependencyProvider.
}
