<?php

namespace Pyz\Zed\Supplier\Business;

use Pyz\Zed\Supplier\Business\Reader\SupplierReader;
use Pyz\Zed\Supplier\Business\Writer\SupplierWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\Supplier\Persistence\SupplierEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\Supplier\Persistence\SupplierRepositoryInterface getRepository()
 */
class SupplierBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\Supplier\Business\Writer\SupplierWriter
     */
    public function createSupplierWriter(): SupplierWriter
    {
        return new SupplierWriter(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Pyz\Zed\Supplier\Business\Reader\SupplierReader
     */
    public function createSupplierReader(): SupplierReader
    {
        return new SupplierReader(
            $this->getRepository(),
        );
    }
}
