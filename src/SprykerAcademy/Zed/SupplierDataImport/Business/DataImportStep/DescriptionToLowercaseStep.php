<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class DescriptionToLowercaseStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (isset($dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION])) {
            $dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION] = strtolower(
                $dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION],
            );
        }
    }
}
