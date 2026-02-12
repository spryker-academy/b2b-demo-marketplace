<?php

namespace Pyz\Zed\SupplierSearch\Persistence;

use Generated\Shared\Transfer\SupplierSearchTransfer;
use Orm\Zed\SupplierSearch\Persistence\PyzSupplierSearch;
use Pyz\Zed\SupplierSearch\Persistence\Exception\SupplierSearchNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\SupplierSearch\Persistence\SupplierSearchPersistenceFactory getFactory()
 */
class SupplierSearchEntityManager extends AbstractEntityManager implements SupplierSearchEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SupplierSearchTransfer $supplierSearchTransfer
     *
     * @return \Generated\Shared\Transfer\SupplierSearchTransfer
     */
    public function createSupplierSearch(
        SupplierSearchTransfer $supplierSearchTransfer,
    ): SupplierSearchTransfer {
        $supplierSearchEntity = $this->getFactory()
            ->createSupplierSearchMapper()
            ->mapSupplierSearchTransferToSupplierSearchEntity(
                $supplierSearchTransfer,
                new PyzSupplierSearch(),
            );

        $supplierSearchEntity->save();

        return $this->getFactory()
            ->createSupplierSearchMapper()
            ->mapSupplierSearchEntityToSupplierSearchTransfer($supplierSearchEntity, $supplierSearchTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierSearchTransfer $supplierSearchTransfer
     *
     * @throws \Pyz\Zed\SupplierSearch\Persistence\Exception\SupplierSearchNotFoundException
     *
     * @return \Generated\Shared\Transfer\SupplierSearchTransfer
     */
    public function updateSupplierSearch(
        SupplierSearchTransfer $supplierSearchTransfer,
    ): SupplierSearchTransfer {
        $supplierSearchEntity = $this->getFactory()
            ->createSupplierSearchQuery()
            ->filterByIdSupplierSearch($supplierSearchTransfer->getIdSupplierSearch())
            ->findOne();

        if ($supplierSearchEntity === null) {
            throw new SupplierSearchNotFoundException(
                sprintf(
                    'SupplierSearch was not found by given id %s',
                    $supplierSearchTransfer->getIdSupplierSearch(),
                ),
            );
        }

        $supplierSearchEntity = $this->getFactory()
            ->createSupplierSearchMapper()
            ->mapSupplierSearchTransferToSupplierSearchEntity(
                $supplierSearchTransfer,
                $supplierSearchEntity,
            );

        $supplierSearchEntity->save();

        return $this->getFactory()
            ->createSupplierSearchMapper()
            ->mapSupplierSearchEntityToSupplierSearchTransfer($supplierSearchEntity, $supplierSearchTransfer);
    }
}
