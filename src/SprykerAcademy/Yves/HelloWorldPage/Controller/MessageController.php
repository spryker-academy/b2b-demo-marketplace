<?php

namespace SprykerAcademy\Yves\HelloWorldPage\Controller;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerAcademy\Yves\HelloWorldPage\HelloWorldPageFactory getFactory()
 */
class MessageController extends AbstractController
{
    public function getAction(string $name)
    {
        $messageCriteriaTransfer = null;
        // TODO: Instantiate MessageCriteriaTransfer and set the message name

        $messageResponseTransfer = null;
        // TODO: Use the HelloWorldClient which is accessible by using `$this->getFactory()`
        // to find a message by a MessageCriteriaTransfer

        return $this->view(
            ['message' => $messageResponseTransfer->getMessage()],
            [],
            '@HelloWorldPage/views/message/get.twig'
        );
    }
}
