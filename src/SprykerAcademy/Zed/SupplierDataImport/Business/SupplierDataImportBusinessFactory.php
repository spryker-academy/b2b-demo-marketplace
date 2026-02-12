<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\SupplierWriterStep;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\DescriptionToLowercaseStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;

class SupplierDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getSupplierDataImport(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($dataImporterConfigurationTransfer);

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        // TODO-3: Add the DescriptionToLowercaseStep to the $dataSetStepBroker
        // Hint: The DataSetStepBroker-class implements the interface `vendor/spryker/data-import/src/Spryker/Zed/DataImport/Business/Model/DataImportStep/DataImportStepAwareInterface.php`

        // TODO-4: Add the WriterStep to the $dataSetStepBroker

        // TODO-5: Add the $dataSetStepBroker to the $dataImporter
        // Hint: The DataImporter-class implements the interface `vendor/spryker/data-import/src/Spryker/Zed/DataImport/Business/Model/DataSet/DataSetStepBrokerAwareInterface.php`

        return $dataImporter;
    }

    // TODO-1: Create the method createDescriptionToLowercaseStep that returns an instance of DescriptionToLowercaseStep

    // TODO-2: Create the method createSupplierWriterStep that returns an instance of SupplierWriterStep
}
