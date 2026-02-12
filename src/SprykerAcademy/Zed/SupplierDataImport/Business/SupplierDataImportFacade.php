<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business;

use Override;
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
    #[Override]
    public function importSupplier(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null,
    ): DataImporterReportTransfer {
        // TODO: Use the factory to get the SupplierDataImport, call the `import()`-method and return its result
        return null;
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
    #[Override]
    public function importSupplierLocation(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null,
    ): DataImporterReportTransfer {
        // TODO: Use the factory to get the SupplierLocationDataImport, call the `import()`-method and return its result
        return null;
    }
}
