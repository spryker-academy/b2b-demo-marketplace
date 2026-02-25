<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Communication\Controller;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Business\HelloWorldFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    public function findMessageAction(MessageCriteriaTransfer $messageCriteria): MessageResponseTransfer
    {
        return $this->getFacade()->findMessage($messageCriteria);
    }

    public function createMessageAction(MessageTransfer $messageTransfer): MessageTransfer
    {
        return $this->getFacade()->createMessage($messageTransfer);
    }
}
