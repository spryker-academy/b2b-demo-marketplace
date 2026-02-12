<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Orm\Zed\SupplierLocation\Persistence\PyzSupplierLocationQuery;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierLocationDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class SupplierLocationWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $supplierLocationEntity = PyzSupplierLocationQuery::create()
            ->filterByIdSupplierLocation($dataSet[SupplierLocationDataSetInterface::COLUMN_ID_SUPPLIER_LOCATION])
            ->findOneOrCreate();

        $supplierLocationEntity->setIdSupplier($dataSet[SupplierLocationDataSetInterface::COLUMN_ID_SUPPLIER]);
        $supplierLocationEntity->setCity($dataSet[SupplierLocationDataSetInterface::COLUMN_CITY]);
        $supplierLocationEntity->setCountry($dataSet[SupplierLocationDataSetInterface::COLUMN_COUNTRY]);
        $supplierLocationEntity->setAddress($dataSet[SupplierLocationDataSetInterface::COLUMN_ADDRESS]);
        $supplierLocationEntity->setZipCode($dataSet[SupplierLocationDataSetInterface::COLUMN_ZIP_CODE]);
        $supplierLocationEntity->setIsDefault($dataSet[SupplierLocationDataSetInterface::COLUMN_IS_DEFAULT] ?? false);

        if ($supplierLocationEntity->isNew() || $supplierLocationEntity->isModified()) {
            $supplierLocationEntity->save();
        }
    }
}
