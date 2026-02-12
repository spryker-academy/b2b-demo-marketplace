<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep;

use Orm\Zed\Supplier\Persistence\Map\PyzMerchantToSupplierTableMap;
use Orm\Zed\Supplier\Persistence\PyzMerchantToSupplier;
use Orm\Zed\Supplier\Persistence\PyzMerchantToSupplierQuery;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Override;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierDataSetInterface;

readonly class SupplierWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    #[Override]
    public function execute(DataSetInterface $dataSet): void
    {
        $name = $dataSet[SupplierDataSetInterface::COLUMN_NAME];
        $description = $dataSet[SupplierDataSetInterface::COLUMN_DESCRIPTION];
        $status = $dataSet[SupplierDataSetInterface::COLUMN_STATUS] ?? 'active';
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
        }

        $this->handleMerchantRelations($supplierEntity->getIdSupplier(), $merchantIds);
    }

    /**
     * @param int $idSupplier
     * @param string $merchantIds
     *
     * @return void
     */
    protected function handleMerchantRelations(int $idSupplier, string $merchantIds): void
    {
        $merchantIdList = array_filter(array_map('intval', array_map('trim', explode(',', $merchantIds))));

        if (!$merchantIdList) {
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
