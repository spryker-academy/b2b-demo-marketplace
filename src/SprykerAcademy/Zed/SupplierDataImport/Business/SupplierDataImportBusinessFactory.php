<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\SupplierWriterStep;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\DescriptionToLowercaseStep;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\SupplierLocationWriterStep;
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

        // TODO: Add the DescriptionToLowercaseStep to the $dataSetStepBroker
        // TODO: Add the SupplierWriterStep to the $dataSetStepBroker
        // TODO: Add the $dataSetStepBroker to the $dataImporter

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getSupplierLocationDataImport(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($dataImporterConfigurationTransfer);

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        // TODO: Add the SupplierLocationWriterStep to the $dataSetStepBroker
        // TODO: Add the $dataSetStepBroker to the $dataImporter

        return $dataImporter;
    }

    /**
     * @return \SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\DescriptionToLowercaseStep
     */
    public function createDescriptionToLowercaseStep(): DescriptionToLowercaseStep
    {
        return new DescriptionToLowercaseStep();
    }

    /**
     * @return \SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\SupplierWriterStep
     */
    public function createSupplierWriterStep(): SupplierWriterStep
    {
        return new SupplierWriterStep();
    }

    /**
     * @return \SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\SupplierLocationWriterStep
     */
    public function createSupplierLocationWriterStep(): SupplierLocationWriterStep
    {
        return new SupplierLocationWriterStep();
    }
}
