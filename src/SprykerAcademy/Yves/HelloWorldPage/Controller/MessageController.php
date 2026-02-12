<?php

namespace SprykerAcademy\Yves\HelloWorldPage\Controller;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerAcademy\Client\HelloWorld\HelloWorldClientInterface;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerAcademy\Yves\HelloWorldPage\HelloWorldPageFactory getFactory()
 */
class MessageController extends AbstractController
{
    public function __construct(protected ?HelloWorldClientInterface $helloWorldClient = null)
    {
        $this->helloWorldClient = $helloWorldClient ?? $this->getFactory()->getHelloWorldClient();
    }

    public function getAction(int $idMessage): View
    {
        $messageCriteriaTransfer = new MessageCriteriaTransfer();
        $messageCriteriaTransfer->setIdMessage($idMessage);

        // TODO: Instantiate MessageCriteriaTransfer and set the message name

        $messageResponseTransfer = $this->helloWorldClient->findMessage($messageCriteriaTransfer);
        // TODO: Use the HelloWorldClient which is accessible by using `$this->getFactory()`
        // to find a message by a MessageCriteriaTransfer

        return $this->view(
            ['message' => $messageResponseTransfer->getMessage()],
            [],
            '@HelloWorldPage/views/message/get.twig'
        );
    }
}
