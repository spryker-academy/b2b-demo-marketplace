<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Business\Reader;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldRepositoryInterface;

class MessageReader
{
    protected HelloWorldRepositoryInterface $helloWorldRepository;

    public function findMessage(MessageCriteriaTransfer $messageCriteria): MessageResponseTransfer
    {
        $messageTransfer = null;// TODO: Use the HelloWorldRepository to find a message

        // TODO: Create and return MessageResponseTransfer
        // and set the properties `isSuccessful` and `message` based on
        // the return value from the HelloWorldRepository
        // Hint: If no message is returned from the repository `isSuccessful` must be false
    }
}
