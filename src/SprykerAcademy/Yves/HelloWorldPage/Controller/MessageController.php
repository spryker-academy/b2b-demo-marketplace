<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Yves\HelloWorldPage\Controller;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerAcademy\Yves\HelloWorldPage\HelloWorldPageFactory getFactory()
 */
class MessageController extends AbstractController
{
    public function getAction(int $idMessage): View
    {
        $messageCriteriaTransfer = new MessageCriteriaTransfer();
        $messageCriteriaTransfer->setIdMessage($idMessage);
        $messageResponseTransfer = $this->getFactory()->getHelloWorldClient()->findMessage($messageCriteriaTransfer);

        return $this->view(
            ['message' => $messageResponseTransfer->getMessage()],
            [],
            '@HelloWorldPage/views/message/get.twig',
        );
    }
}
