<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierDataSetInterface;

readonly class DescriptionToLowercaseStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     */
    #[\Override]
    public function execute(DataSetInterface $dataSet): void
    {
        if (!isset($dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION])) {
            return;
        }

        $dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION] = strtolower(
            $dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION],
        );
    }
}
