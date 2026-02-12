<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Communication\Plugin\DataImport;

use Override;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use SprykerAcademy\Zed\SupplierDataImport\SupplierDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerAcademy\Zed\SupplierDataImport\Business\SupplierDataImportFacadeInterface getFacade()
 * @method \SprykerAcademy\Zed\SupplierDataImport\SupplierDataImportConfig getConfig()
 */
class SupplierDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
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
    #[Override]
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null,
    ): DataImporterReportTransfer {
        return $this->getFacade()->importSupplier($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    #[Override]
    public function getImportType(): string
    {
        return SupplierDataImportConfig::IMPORT_TYPE_SUPPLIER;
    }
}
