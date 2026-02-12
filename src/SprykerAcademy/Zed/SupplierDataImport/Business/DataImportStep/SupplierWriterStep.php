<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class SupplierWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        // TODO-1: Find or create an instance of supplier entity
        // Hint-1: PyzSupplierQuery has a static method `create()`
        // Hint-2: Filter by name by calling 'filterByName()' method
        // Hint-3: `findOneOrCreate()` can be used to query one from the database or create a fresh entity
        $supplierEntity = null;

        // TODO-2: Assign the description from the dataset to the entity by using the setter

        // TODO-3: Save the entity ONLY if it's new or modified
        // Hint: Take a look at `src/Orm/Zed/Supplier/Persistence/Base/PyzSupplier.php` for the right methods
    }
}
