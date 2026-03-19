<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\ContactRequest\Communication\Controller;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerAcademy\Zed\ContactRequest\Business\ContactRequestFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    public function findMessageAction(ContactRequestCriteriaTransfer $contactRequestCriteria)
    {
        // TODO: With the help of the facade find a message and return it
    }
}
