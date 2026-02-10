<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Communication\Controller;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerAcademy\Zed\HelloWorld\Business\HelloWorldFacadeInterface;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Business\HelloWorldFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    protected $facade;

    public function __construct(HelloWorldFacadeInterface $facade)
    {
        $this->facade = $facade;
    }

    public function findMessageAction(MessageCriteriaTransfer $messageCriteria): MessageResponseTransfer
    {
        return $this->facade->findMessage($messageCriteria);
    }
}
