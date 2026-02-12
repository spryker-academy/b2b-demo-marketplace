<?php

namespace Pyz\Zed\SupplierSearch\Business;

use Pyz\Zed\Supplier\Business\SupplierFacadeInterface;
use Pyz\Zed\SupplierSearch\SupplierSearchDependencyProvider;
use Pyz\Zed\SupplierSearch\Business\Writer\SupplierSearchWriter;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\SupplierSearch\Persistence\SupplierSearchRepositoryInterface getRepository()
 * @method \Pyz\Zed\SupplierSearch\Persistence\SupplierSearchEntityManagerInterface getEntityManager()
 */
class SupplierSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\SupplierSearch\Business\Writer\SupplierSearchWriter
     */
    public function createSupplierSearchWriter(): SupplierSearchWriter
    {
        return new SupplierSearchWriter(
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
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Pyz\Zed\Supplier\Business\SupplierFacadeInterface
     */
    public function getSupplierFacade(): SupplierFacadeInterface
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::FACADE_ANTELOPE);
    }
}
