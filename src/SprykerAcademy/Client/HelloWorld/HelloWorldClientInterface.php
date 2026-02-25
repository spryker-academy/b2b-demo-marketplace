<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Client\HelloWorld;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

interface HelloWorldClientInterface
{
    /**
     * Specification:
     * - Finds message by defined criteria
     * - Returns a response-transfer which can hold a message if one is found
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageCriteriaTransfer $messageCriteria
     *
     * @return \Generated\Shared\Transfer\MessageResponseTransfer
     */
    public function findMessage(MessageCriteriaTransfer $messageCriteria): MessageResponseTransfer;

    /**
     * Specification:
     * - Creates a message by message-transfer
     * - Returns a message-transfer with the created message-data
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function createMessage(MessageTransfer $messageTransfer): MessageTransfer;
}
