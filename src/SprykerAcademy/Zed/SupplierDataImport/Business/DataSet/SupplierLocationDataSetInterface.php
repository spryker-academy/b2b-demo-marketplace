<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataSet;

interface SupplierLocationDataSetInterface
{
    public const string COLUMN_SUPPLIER_NAME = 'supplier_name';

    public const string COLUMN_CITY = 'city';

    public const string COLUMN_COUNTRY = 'country';

    public const string COLUMN_ADDRESS = 'address';

    public const string COLUMN_ZIP_CODE = 'zip_code';

    public const string COLUMN_IS_DEFAULT = 'is_default';
}
