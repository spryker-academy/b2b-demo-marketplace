<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\HelloWorld\Persistence;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageTransfer;

interface HelloWorldRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageCriteriaTransfer $messageCriteria
     */
    public function findMessage(MessageCriteriaTransfer $messageCriteria): ?MessageTransfer;
}
