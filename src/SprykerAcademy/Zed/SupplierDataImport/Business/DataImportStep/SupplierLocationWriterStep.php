<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Orm\Zed\SupplierLocation\Persistence\PyzSupplierLocationQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierLocationDataSetInterface;

class SupplierLocationWriterStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected static array $supplierCache = [];

    /**
     * @param \Orm\Zed\Supplier\Persistence\PyzSupplierQuery $supplierQuery
     * @param \Orm\Zed\SupplierLocation\Persistence\PyzSupplierLocationQuery $supplierLocationQuery
     */
    public function __construct(
        protected PyzSupplierQuery $supplierQuery,
        protected PyzSupplierLocationQuery $supplierLocationQuery,
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     */
    #[\Override]
    public function execute(DataSetInterface $dataSet): void
    {
        $supplierName = $dataSet[SupplierLocationDataSetInterface::COLUMN_SUPPLIER_NAME];
        $address = $dataSet[SupplierLocationDataSetInterface::COLUMN_ADDRESS];
        $city = $dataSet[SupplierLocationDataSetInterface::COLUMN_CITY];
        $country = $dataSet[SupplierLocationDataSetInterface::COLUMN_COUNTRY];
        $zipCode = $dataSet[SupplierLocationDataSetInterface::COLUMN_ZIP_CODE];
        $isDefault = $dataSet[SupplierLocationDataSetInterface::COLUMN_IS_DEFAULT] ?? false;

        $supplierId = $this->getSupplierId($supplierName);

        $supplierLocationEntity = $this->supplierLocationQuery
            ->filterByFkSupplier($supplierId)
            ->filterByAddress($address)
            ->findOneOrCreate();

        $supplierLocationEntity->setCity($city);
        $supplierLocationEntity->setCountry($country);
        $supplierLocationEntity->setZipCode($zipCode);
        $supplierLocationEntity->setIsDefault($isDefault);

        if (!$supplierLocationEntity->isNew() && !$supplierLocationEntity->isModified()) {
            return;
        }

        $supplierLocationEntity->save();
    }

    /**
     * @param string $supplierName
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     */
    protected function getSupplierId(string $supplierName): int
    {
        if (isset(static::$supplierCache[$supplierName])) {
            return static::$supplierCache[$supplierName];
        }

        $supplierEntity = $this->supplierQuery
            ->clear()
            ->filterByName($supplierName)
            ->findOne();

        if (!$supplierEntity) {
            throw new EntityNotFoundException(sprintf('Supplier with name "%s" not found.', $supplierName));
        }

        static::$supplierCache[$supplierName] = $supplierEntity->getIdSupplier();

        return static::$supplierCache[$supplierName];
    }
}
