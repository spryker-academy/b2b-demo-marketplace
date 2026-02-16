<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierSearch\Reader;

use Generated\Shared\Transfer\SupplierCollectionTransfer;
use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierSearchReaderInterface
{
    /**
     * @param array<mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SupplierCollectionTransfer
     */
    public function searchSuppliers(array $requestParameters = []): SupplierCollectionTransfer;

    /**
     * @param int $idSupplier
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer
     */
    public function findSupplierById(int $idSupplier): SupplierTransfer;
}
