<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\HelloWorld\Persistence;

use Generated\Shared\Transfer\MessageTransfer;

interface HelloWorldEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     */
    public function createMessage(MessageTransfer $messageTransfer): MessageTransfer;
}
