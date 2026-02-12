<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class SupplierWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $supplierEntity = PyzSupplierQuery::create()
            ->filterByName($dataSet[SupplierDataSetInterface::COLUMN_NAME])
            ->findOneOrCreate();

        $supplierEntity->setDescription($dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION]);
        $supplierEntity->setStatus($dataSet[SupplierDataSetInterface::COLUMN_STATUS] ?? 'active');
        $supplierEntity->setEmail($dataSet[SupplierDataSetInterface::COLUMN_EMAIL] ?? null);
        $supplierEntity->setPhone($dataSet[SupplierDataSetInterface::COLUMN_PHONE] ?? null);

        if ($supplierEntity->isNew() || $supplierEntity->isModified()) {
            $supplierEntity->save();
            $this->addPublishEvents(SupplierSearchConfig::SUPPLIER_PUBLISH, $supplierEntity->getIdSupplier());
        }
    }
}
