<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Override;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

readonly class SupplierLocationWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    #[Override]
    public function execute(DataSetInterface $dataSet): void
    {
        // TODO-1: Find the supplier entity by name (from the dataset)
        // Hint: Use PyzSupplierQuery and filter by $dataSet[SupplierLocationDataSetInterface::COLUMN_SUPPLIER_NAME]

        // TODO-2: Find or create an instance of supplier location entity
        // Hint: Filter by fk_supplier and address

        // TODO-3: Assign city, country, zip_code and is_default from the dataset to the entity

        // TODO-4: Save the entity ONLY if it's new or modified
    }
}
