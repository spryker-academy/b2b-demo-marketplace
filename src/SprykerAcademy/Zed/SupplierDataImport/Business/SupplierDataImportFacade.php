<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerAcademy\Zed\SupplierDataImport\Business\SupplierDataImportBusinessFactory getFactory()
 */
class SupplierDataImportFacade extends AbstractFacade implements SupplierDataImportFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importSupplier(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null,
    ): DataImporterReportTransfer {
        return $this->getFactory()
            ->getSupplierDataImport($dataImporterConfigurationTransfer)
            ->import($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importSupplierLocation(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null,
    ): DataImporterReportTransfer {
        return $this->getFactory()
            ->getSupplierLocationDataImport($dataImporterConfigurationTransfer)
            ->import($dataImporterConfigurationTransfer);
    }
}
