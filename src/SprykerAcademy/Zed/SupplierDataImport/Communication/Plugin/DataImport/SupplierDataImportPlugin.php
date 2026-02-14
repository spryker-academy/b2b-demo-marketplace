<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use SprykerAcademy\Zed\SupplierDataImport\SupplierDataImportConfig;

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
     */
    #[\Override]
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null,
    ): DataImporterReportTransfer {
        return $this->getFacade()->importSupplier($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    #[\Override]
    public function getImportType(): string
    {
        return SupplierDataImportConfig::IMPORT_TYPE_SUPPLIER;
    }
}
