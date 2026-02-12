<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\SupplierDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Orm\Zed\SupplierLocation\Persistence\PyzSupplierLocationQuery;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\DescriptionToLowercaseStep;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\SupplierLocationWriterStep;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\SupplierWriterStep;

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
        $dataSetStepBroker->addStep($this->createDescriptionToLowercaseStep());
        $dataSetStepBroker->addStep($this->createSupplierWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

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
        $dataSetStepBroker->addStep($this->createSupplierLocationWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

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
        return new SupplierLocationWriterStep(
            $this->createSupplierQuery(),
            $this->createSupplierLocationQuery(),
        );
    }

    /**
     * @return \Orm\Zed\Supplier\Persistence\PyzSupplierQuery
     */
    public function createSupplierQuery(): PyzSupplierQuery
    {
        return PyzSupplierQuery::create();
    }

    /**
     * @return \Orm\Zed\SupplierLocation\Persistence\PyzSupplierLocationQuery
     */
    public function createSupplierLocationQuery(): PyzSupplierLocationQuery
    {
        return PyzSupplierLocationQuery::create();
    }
}
