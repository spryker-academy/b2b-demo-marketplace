<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Communication\Controller;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Business\HelloWorldFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    public function findMessageAction(MessageCriteriaTransfer $messageCriteria)
    {
        // TODO: With the help of the facade find a message and return it
    }
}
