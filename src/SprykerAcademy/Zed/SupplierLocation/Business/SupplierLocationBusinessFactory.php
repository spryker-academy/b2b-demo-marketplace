<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierLocation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerAcademy\Zed\SupplierLocation\Business\Reader\SupplierLocationReader;

/**
 * @method \SprykerAcademy\Zed\SupplierLocation\Persistence\SupplierLocationRepositoryInterface getRepository()
 */
class SupplierLocationBusinessFactory extends AbstractBusinessFactory
{
    public function createSupplierLocationReader(): SupplierLocationReader
    {
        return new SupplierLocationReader(
            $this->getRepository(),
        );
    }
}
