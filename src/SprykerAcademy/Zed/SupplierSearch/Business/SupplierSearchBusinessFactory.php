<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Business;

use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface;
use SprykerAcademy\Zed\SupplierSearch\Business\Writer\SupplierSearchWriter;
use SprykerAcademy\Zed\SupplierSearch\SupplierSearchDependencyProvider;

/**
 * @method \SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchRepositoryInterface getRepository()
 * @method \SprykerAcademy\Zed\SupplierSearch\Persistence\SupplierSearchEntityManagerInterface getEntityManager()
 */
class SupplierSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerAcademy\Zed\SupplierSearch\Business\Writer\SupplierSearchWriter
     */
    public function createSupplierSearchWriter(): SupplierSearchWriter
    {
        // TODO-3: Provide all dependencies to the SupplierSearchWriter constructor.
        // Hint-1: getRepository() returns the persistence repository.
        // Hint-2: getEntityManager() returns the persistence entity manager.
        return new SupplierSearchWriter();
    }

    // TODO-1: Create getEventBehaviorFacade() and return EventBehaviorFacade.
    // Hint-1: Use getProvidedDependency() with key `SupplierSearchDependencyProvider::FACADE_EVENT_BEHAVIOR`.

    // TODO-2: Create getSupplierFacade() and return SupplierFacade.
    // Hint-1: Use getProvidedDependency() with key `SupplierSearchDependencyProvider::FACADE_SUPPLIER`.
}
