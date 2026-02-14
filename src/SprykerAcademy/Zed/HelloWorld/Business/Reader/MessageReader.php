<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\HelloWorld\Business\Reader;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldRepositoryInterface;

class MessageReader
{
    public function __construct(protected HelloWorldRepositoryInterface $helloWorldRepository)
    {
    }

    public function findMessage(MessageCriteriaTransfer $messageCriteria): MessageResponseTransfer
    {
        $messageTransfer = $this->helloWorldRepository->findMessage($messageCriteria);
        $messageResponseTransfer = new MessageResponseTransfer();

        if ($messageTransfer === null) {
            $messageResponseTransfer->setIsSuccessful(false);
        } else {
            $messageResponseTransfer->setIsSuccessful(true);
            $messageResponseTransfer->setMessage($messageTransfer);
        }

        return $messageResponseTransfer;
    }
}
