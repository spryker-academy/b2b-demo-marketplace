<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Client\HelloWorld;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;

interface HelloWorldClientInterface
{
    /**
     * - Finds message by defined criteria
     * - Returns a response-transfer which can hold a message if one is found
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageCriteriaTransfer $messageCriteria
     */
    public function findMessage(MessageCriteriaTransfer $messageCriteria): MessageResponseTransfer;
}
