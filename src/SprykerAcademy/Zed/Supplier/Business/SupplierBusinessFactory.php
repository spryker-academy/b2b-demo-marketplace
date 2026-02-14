<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\Supplier\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerAcademy\Zed\Supplier\Business\Reader\SupplierReader;
use SprykerAcademy\Zed\Supplier\Business\Writer\SupplierWriter;

/**
 * @method \SprykerAcademy\Zed\Supplier\Persistence\SupplierEntityManagerInterface getEntityManager()
 * @method \SprykerAcademy\Zed\Supplier\Persistence\SupplierRepositoryInterface getRepository()
 */
class SupplierBusinessFactory extends AbstractBusinessFactory
{
    public function createSupplierWriter(): SupplierWriter
    {
        return new SupplierWriter(
            $this->getEntityManager(),
        );
    }

    public function createSupplierReader(): SupplierReader
    {
        return new SupplierReader(
            $this->getRepository(),
        );
    }
}
