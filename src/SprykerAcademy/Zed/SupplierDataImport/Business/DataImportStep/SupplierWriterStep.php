<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Orm\Zed\Supplier\Persistence\Map\PyzMerchantToSupplierTableMap;
use Orm\Zed\Supplier\Persistence\PyzMerchantToSupplier;
use Orm\Zed\Supplier\Persistence\PyzMerchantToSupplierQuery;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;
use SprykerAcademy\Shared\SupplierStorage\SupplierStorageConfig;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierDataSetInterface;

class SupplierWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const int STATUS_ACTIVE = 1;

    protected const int STATUS_INACTIVE = 0;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     */
    #[\Override]
    public function execute(DataSetInterface $dataSet): void
    {
        $name = $dataSet[SupplierDataSetInterface::COLUMN_NAME];

        $supplierEntity = PyzSupplierQuery::create()
            ->filterByName($name)
            ->findOneOrCreate();

        $supplierEntity
            ->setDescription($dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION])
            ->setStatus($this->normalizeStatus($dataSet[SupplierDataSetInterface::COLUMN_STATUS] ?? null))
            ->setEmail($dataSet[SupplierDataSetInterface::COLUMN_EMAIL] ?? null)
            ->setPhone($dataSet[SupplierDataSetInterface::COLUMN_PHONE] ?? null);

        if ($supplierEntity->isNew() || $supplierEntity->isModified()) {
            $supplierEntity->save();
            $this->addPublishEvents(SupplierSearchConfig::SUPPLIER_PUBLISH, $supplierEntity->getIdSupplier());
            $this->addPublishEvents(SupplierStorageConfig::SUPPLIER_PUBLISH, $supplierEntity->getIdSupplier());
        }

        $this->handleMerchantRelations(
            $supplierEntity->getIdSupplier(),
            (string)($dataSet[SupplierDataSetInterface::COLUMN_MERCHANT_IDS] ?? ''),
        );
    }

    /**
     * @param mixed $status
     */
    protected function normalizeStatus(mixed $status): int
    {
        if ($status === null || $status === '') {
            return static::STATUS_ACTIVE;
        }

        if ((string)$status === '1') {
            return static::STATUS_ACTIVE;
        }

        if ((string)$status === '0') {
            return static::STATUS_INACTIVE;
        }

        if (mb_strtolower((string)$status) === 'active') {
            return static::STATUS_ACTIVE;
        }

        return static::STATUS_INACTIVE;
    }

    /**
     * @param int $idSupplier
     * @param string $merchantIds
     */
    protected function handleMerchantRelations(int $idSupplier, string $merchantIds): void
    {
        $merchantIdList = $this->extractMerchantIds($merchantIds);

        if ($merchantIdList === []) {
            return;
        }

        $existingMerchantIds = PyzMerchantToSupplierQuery::create()
            ->filterByFkSupplier($idSupplier)
            ->filterByFkMerchant_In($merchantIdList)
            ->select([PyzMerchantToSupplierTableMap::COL_FK_MERCHANT])
            ->find()
            ->toArray();

        $newMerchantIds = array_diff($merchantIdList, $existingMerchantIds);

        if ($newMerchantIds === []) {
            return;
        }

        foreach ($newMerchantIds as $idMerchant) {
            (new PyzMerchantToSupplier())
                ->setFkSupplier($idSupplier)
                ->setFkMerchant((int)$idMerchant)
                ->save();
        }
    }

    /**
     * @param string $merchantIds
     *
     * @return list<int>
     */
    protected function extractMerchantIds(string $merchantIds): array
    {
        $merchantIds = array_filter(array_map('trim', explode(',', $merchantIds)));

        if ($merchantIds === []) {
            return [];
        }

        return array_values(array_unique(array_map('intval', $merchantIds)));
    }
}
