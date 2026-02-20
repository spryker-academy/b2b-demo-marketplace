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
        $description = $dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION];
        $status = $this->normalizeStatus($dataSet[SupplierDataSetInterface::COLUMN_STATUS] ?? null);
        $email = $dataSet[SupplierDataSetInterface::COLUMN_EMAIL] ?? null;
        $phone = $dataSet[SupplierDataSetInterface::COLUMN_PHONE] ?? null;
        $merchantIds = $dataSet[SupplierDataSetInterface::COLUMN_MERCHANT_IDS] ?? '';

        $supplierEntity = PyzSupplierQuery::create()
            ->filterByName($name)
            ->findOneOrCreate();

        $supplierEntity->setDescription($description);
        $supplierEntity->setStatus($status);
        $supplierEntity->setEmail($email);
        $supplierEntity->setPhone($phone);

        if ($supplierEntity->isNew() || $supplierEntity->isModified()) {
            $supplierEntity->save();
            // TODO-1: Use the `addPublishEvents` method to trigger publish events for both Search and Storage.
            // Hint-1: Call it twice - once for SupplierSearchConfig::SUPPLIER_PUBLISH and once for SupplierStorageConfig::SUPPLIER_PUBLISH
            // Hint-2: The second parameter is the supplier's ID from the entity `$supplierEntity`.
            // Example: $this->addPublishEvents(SupplierSearchConfig::SUPPLIER_PUBLISH, $supplierEntity->getIdSupplier());
        }

        $this->handleMerchantRelations($supplierEntity->getIdSupplier(), $merchantIds);
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
        $merchantIdList = array_filter(array_map('intval', array_map('trim', explode(',', $merchantIds))));

        if ($merchantIdList === []) {
            return;
        }

        // 1. Find existing relations for this supplier in one query
        $existingMerchantIds = PyzMerchantToSupplierQuery::create()
            ->filterByFkSupplier($idSupplier)
            ->select([PyzMerchantToSupplierTableMap::COL_FK_MERCHANT])
            ->find()
            ->toArray();

        // 2. Filter out IDs that already have a relation
        $newMerchantIds = array_diff($merchantIdList, $existingMerchantIds);

        // 3. Create only the missing relations
        foreach ($newMerchantIds as $idMerchant) {
            $relationEntity = new PyzMerchantToSupplier();
            $relationEntity->setFkSupplier($idSupplier);
            $relationEntity->setFkMerchant($idMerchant);
            $relationEntity->save();
        }
    }
}
