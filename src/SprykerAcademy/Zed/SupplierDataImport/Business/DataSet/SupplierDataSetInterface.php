<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataSet;

interface SupplierDataSetInterface
{
    public const string COLUMN_NAME = 'supplier_name';

    public const string COLUMN_DESCRIPTION = 'description';

    public const string COLUMN_STATUS = 'status';

    public const string COLUMN_EMAIL = 'email';

    public const string COLUMN_PHONE = 'phone';

    public const string COLUMN_MERCHANT_IDS = 'merchant_ids';
}
