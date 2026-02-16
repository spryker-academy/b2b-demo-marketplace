<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\SupplierSearch\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\Map\PyzSupplierTableMap;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;

class SupplierPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @var string
     */
    protected const string COL_ID_SUPPLIER = PyzSupplierTableMap::COL_ID_SUPPLIER;

    public function getData(int $offset, int $limit): array
    {
        $supplierEntities = PyzSupplierQuery::create()
            ->offset($offset)
            ->limit($limit)
            ->find();

        $transfers = [];
        foreach ($supplierEntities as $entity) {
            $transfers[] = (new SupplierTransfer())->fromArray($entity->toArray(), true);
        }

        return $transfers;
    }

    public function getResourceName(): string
    {
        return 'supplier';
    }

    public function getEventName(): string
    {
        return SupplierSearchConfig::SUPPLIER_PUBLISH;
    }

    public function getIdColumnName(): ?string
    {
        return static::COL_ID_SUPPLIER;
    }
}
