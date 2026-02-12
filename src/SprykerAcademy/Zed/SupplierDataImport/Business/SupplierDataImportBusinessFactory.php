<?php

namespace Pyz\Zed\SupplierDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Pyz\Zed\SupplierDataImport\Business\DataImportStep\SupplierWriterStep;
use Pyz\Zed\SupplierDataImport\Business\DataImportStep\ColorToLowercaseStep;
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
        $dataSetStepBroker->addStep($this->createColorToLowercaseStep());
        $dataSetStepBroker->addStep($this->createSupplierWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\SupplierDataImport\Business\DataImportStep\ColorToLowercaseStep
     */
    public function createColorToLowercaseStep(): ColorToLowercaseStep
    {
        return new ColorToLowercaseStep();
    }

    /**
     * @return \Pyz\Zed\SupplierDataImport\Business\DataImportStep\SupplierWriterStep
     */
    public function createSupplierWriterStep(): SupplierWriterStep
    {
        return new SupplierWriterStep();
    }
}
