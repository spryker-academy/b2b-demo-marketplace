<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierDataImport;

use Spryker\Zed\DataImport\DataImportConfig;

class SupplierDataImportConfig extends DataImportConfig
{
    public const string IMPORT_TYPE_SUPPLIER = 'supplier';

    public const string IMPORT_TYPE_SUPPLIER_LOCATION = 'supplier-location';
}
