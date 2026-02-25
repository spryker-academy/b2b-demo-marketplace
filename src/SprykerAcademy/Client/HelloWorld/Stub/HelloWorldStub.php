<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Client\HelloWorld\Stub;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class HelloWorldStub
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected ZedRequestClientInterface $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    public function findMessage(MessageCriteriaTransfer $messageCriteria): MessageResponseTransfer
    {
        /** @var MessageResponseTransfer $messageResponseTransfer */
        $messageResponseTransfer = $this->zedRequestClient->call('/hello-world/gateway/find-message', $messageCriteria);

        return $messageResponseTransfer;
    }

    public function createMessage(MessageTransfer $messageTransfer): MessageTransfer
    {
        /** @var \Generated\Shared\Transfer\MessageTransfer $messageTransfer */
        $messageTransfer = $this->zedRequestClient->call('/hello-world/gateway/create-message', $messageTransfer);

        return $messageTransfer;
    }
}
