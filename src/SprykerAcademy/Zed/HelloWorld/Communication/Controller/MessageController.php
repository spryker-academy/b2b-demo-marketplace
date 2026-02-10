<?php

namespace SprykerAcademy\Zed\HelloWorld\Communication\Controller;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Business\HelloWorldFacadeInterface getFacade()
 */
class MessageController extends AbstractController
{
    public function addAction(Request $request)
    {
        $messageName = $request->query->get('name', 'Oskar');

        $messageCriteriaTransfer = null;
        // TODO: Instantiate MessageCriteriaTransfer and set the message name

        $messageResponseTransfer = $this->getFacade()
            ->findMessage($messageCriteriaTransfer);

        $messageTransfer = $messageResponseTransfer->getMessage();

        if (!$messageTransfer) {
            // TODO: If there isn't a message with that name already,
            // create a MessageTransfer and set the right message name
            // and persist it with the help of the method `$this->getFacade()->createMessage()`
        }

        return $this->viewResponse([
            'message' => $messageTransfer,
        ]);
    }
}
