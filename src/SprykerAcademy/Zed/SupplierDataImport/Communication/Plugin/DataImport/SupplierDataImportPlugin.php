<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use SprykerAcademy\Zed\SupplierDataImport\SupplierDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerAcademy\Zed\SupplierDataImport\Business\SupplierDataImportFacadeInterface getFacade()
 */
class SupplierDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    // TODO: Implement the required interface methods
    // Hint-1: `getFacade()` provides you the SupplierDataImportFacade with import functionality
    // Hint-2: SupplierDataImportConfig provides a constant for the import type
}
