<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Client\HelloWorld\Stub;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
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
        /** @var \Generated\Shared\Transfer\MessageResponseTransfer $messageResponseTransfer */

        // TODO: Fill in the right path for '/module-name/controller-name/action-name'
        // Hint: We want to call the src/SprykerAcademy/Zed/HelloWorld/Communication/Controller/GatewayController.php::findMessageAction()

        $messageResponseTransfer = $this->zedRequestClient->call('/module-name/controller-name/action-name', $messageCriteria);

        return $messageResponseTransfer;
    }
}
