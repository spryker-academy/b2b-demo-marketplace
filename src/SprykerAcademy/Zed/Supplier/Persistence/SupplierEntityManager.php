<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\Supplier\Persistence;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\PyzSupplier;
use Override;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class SupplierEntityManager extends AbstractEntityManager implements SupplierEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    #[Override]
    public function createSupplier(SupplierTransfer $supplierTransfer): SupplierTransfer
    {
        $supplierEntity = new PyzSupplier();
        $supplierEntity->fromArray($supplierTransfer->modifiedToArray());
        $supplierEntity->save();

        return $supplierTransfer->fromArray($supplierEntity->toArray(), true);
    }
}
