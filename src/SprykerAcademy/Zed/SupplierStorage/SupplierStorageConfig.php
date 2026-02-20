<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SupplierStorageConfig extends AbstractBundleConfig
{
    /**
     * @return string|null
     */
    public function getSynchronizationPoolName(): ?string
    {
        return null;
    }
}
