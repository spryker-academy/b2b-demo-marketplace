<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Override;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

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
