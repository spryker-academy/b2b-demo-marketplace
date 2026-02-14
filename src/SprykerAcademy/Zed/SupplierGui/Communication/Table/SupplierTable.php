<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication\Table;

use Orm\Zed\Supplier\Persistence\Map\PyzSupplierTableMap;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerAcademy\Zed\SupplierGui\Communication\Controller\DeleteController;
use SprykerAcademy\Zed\SupplierGui\Communication\Controller\EditController;

class SupplierTable extends AbstractTable
{
    public const COL_ID_SUPPLIER = PyzSupplierTableMap::COL_ID_SUPPLIER;

    public const COL_NAME = PyzSupplierTableMap::COL_NAME;

    public const COL_DESCRIPTION = PyzSupplierTableMap::COL_DESCRIPTION;

    public const COL_STATUS = PyzSupplierTableMap::COL_STATUS;

    public const COL_EMAIL = PyzSupplierTableMap::COL_EMAIL;

    public const COL_PHONE = PyzSupplierTableMap::COL_PHONE;

    protected const COL_ACTIONS = 'actions';

    protected const URL_SUPPLIER_EDIT = '/supplier-gui/edit';

    protected const URL_SUPPLIER_DELETE = '/supplier-gui/delete';

    /**
     * @param \Orm\Zed\Supplier\Persistence\PyzSupplierQuery $supplierQuery
     */
    public function __construct(protected PyzSupplierQuery $supplierQuery)
    {
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     */
    #[\Override]
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_SUPPLIER => 'ID',
            static::COL_NAME => 'Name',
            static::COL_DESCRIPTION => 'Description',
            static::COL_STATUS => 'Status',
            static::COL_EMAIL => 'Email',
            static::COL_PHONE => 'Phone',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            static::COL_ID_SUPPLIER,
            static::COL_NAME,
            static::COL_DESCRIPTION,
            static::COL_STATUS,
            static::COL_EMAIL,
            static::COL_PHONE,
        ]);

        $config->setSearchable([
            static::COL_NAME,
            static::COL_DESCRIPTION,
            static::COL_EMAIL,
            static::COL_PHONE,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     */
    #[\Override]
    protected function prepareData(TableConfiguration $config): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Supplier\Persistence\PyzSupplier> $supplierEntityCollection */
        $supplierEntityCollection = $this->runQuery($this->supplierQuery, $config, true);

        return $this->mapReturns($supplierEntityCollection);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Supplier\Persistence\PyzSupplier> $supplierEntityCollection
     */
    protected function mapReturns(ObjectCollection $supplierEntityCollection): array
    {
        $returns = [];

        foreach ($supplierEntityCollection as $supplierEntity) {
            $returns[] = [
                static::COL_ID_SUPPLIER => $supplierEntity->getIdSupplier(),
                static::COL_NAME => $supplierEntity->getName(),
                static::COL_DESCRIPTION => $supplierEntity->getDescription(),
                static::COL_STATUS => $supplierEntity->getStatus(),
                static::COL_EMAIL => $supplierEntity->getEmail(),
                static::COL_PHONE => $supplierEntity->getPhone(),
                static::COL_ACTIONS => $this->buildActionButtons($supplierEntity->getIdSupplier()),
            ];
        }

        return $returns;
    }

    /**
     * @param int $idSupplier
     */
    protected function buildActionButtons(int $idSupplier): string
    {
        return implode(' ', [
            $this->generateEditButton(
                Url::generate(static::URL_SUPPLIER_EDIT, [
                    EditController::REQUEST_PARAM_ID_SUPPLIER => $idSupplier,
                ]),
                'Edit',
            ),
            $this->generateRemoveButton(
                Url::generate(static::URL_SUPPLIER_DELETE, [
                    DeleteController::REQUEST_PARAM_ID_SUPPLIER => $idSupplier,
                ]),
                'Delete',
            ),
        ]);
    }
}
