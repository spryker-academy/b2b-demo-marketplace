<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Override;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierDataSetInterface;

readonly class DescriptionToLowercaseStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    #[Override]
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION] = strtolower($dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION]);
    }
}
