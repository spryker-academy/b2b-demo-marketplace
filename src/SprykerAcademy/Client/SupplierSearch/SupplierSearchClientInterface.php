<?php

namespace Pyz\Client\SupplierSearch;

use Generated\Shared\Transfer\SupplierTransfer;

interface SupplierSearchClientInterface
{
    /**
     * Specification:
     * - Searches for an supplier by a given name and returns the first match
     *
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer|null
     */
    public function getSupplierByName(string $name): ?SupplierTransfer;
}
