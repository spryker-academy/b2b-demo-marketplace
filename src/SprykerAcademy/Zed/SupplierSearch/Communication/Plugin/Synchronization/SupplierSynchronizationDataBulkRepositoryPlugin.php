<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierSearch\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;

/**
 * @method \SprykerAcademy\Zed\SupplierSearch\Business\SupplierSearchFacadeInterface getFacade()
 */
class SupplierSynchronizationDataBulkRepositoryPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
{
    /**
     * @var string
     */
    protected const PARAM_TYPE = 'supplier';

    /**
     * {@inheritDoc}
     *
     * @api
     */
    #[\Override]
    public function getResourceName(): string
    {
        return SupplierSearchConfig::SUPPLIER_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    #[\Override]
    public function hasStore(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string, string>
     */
    #[\Override]
    public function getParams(): array
    {
        return ['type' => static::PARAM_TYPE];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    #[\Override]
    public function getQueueName(): string
    {
        return SupplierSearchConfig::SUPPLIER_SYNC_SEARCH_QUEUE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    #[\Override]
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        return $this->getFacade()
            ->getSynchronizationDataTransfersBySupplierIds(
                (new FilterTransfer())->setOffset($offset)->setLimit($limit),
                $ids,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    #[\Override]
    public function getSynchronizationQueuePoolName(): ?string
    {
        return null;
    }
}
