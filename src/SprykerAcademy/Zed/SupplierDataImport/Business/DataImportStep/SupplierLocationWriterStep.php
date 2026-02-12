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
        // TODO-1: Find or create an instance of supplier location entity by joining with the supplier table
        // Hint-1: Use PyzSupplierLocationQuery::create()
        // Hint-2: Use usePyzSupplierQuery() to join and filter by the supplier name from the dataset
        // Hint-3: Filter by address from the dataset
        // Hint-4: Use findOneOrCreate()
        $supplierLocationEntity = null;

        // TODO-2: If the entity is new, you must find the supplier ID and set it
        // Hint: Since findOneOrCreate() won't automatically set the foreign key from a joined filter,
        // you need to ensure fk_supplier is set for new entities.

        // TODO-3: Assign city, country, zip_code and is_default from the dataset to the entity

        // TODO-4: Save the entity ONLY if it's new or modified
    }
}
