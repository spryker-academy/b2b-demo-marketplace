<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Override;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

readonly class SupplierWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    #[Override]
    public function execute(DataSetInterface $dataSet): void
    {
        // TODO-1: Find or create an instance of supplier entity
        // Hint-1: PyzSupplierQuery has a static method `create()`
        // Hint-2: Filter by name by calling 'filterByName()' method
        // Hint-3: `findOneOrCreate()` can be used to query one from the database or create a fresh entity
        $supplierEntity = null;

        // TODO-2: Assign the description, status, email and phone from the dataset to the entity by using the setters

        // TODO-3: Save the entity ONLY if it's new or modified

        // TODO-4: Handle the many-to-many relationship with merchants
        // Hint-1: The merchant IDs are in $dataSet[SupplierDataSetInterface::COLUMN_MERCHANT_IDS] as a comma-separated string
        // Hint-2: Use PyzMerchantToSupplierQuery to manage the relations
    }
}
