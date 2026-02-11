<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Business\HelloWorldFacadeInterface getFacade()
 */
class MessageController extends AbstractController
{
    public function addAction(Request $request)
    {
        $message = $request->query->get('message', 'Hello World');

        $messageCriteriaTransfer = null;
        // TODO: Instantiate MessageCriteriaTransfer and set the message
// Use the facade with the method $this->getFacadde() and calle findMessage() with the message criteria transfer
        //Assign the return value to
        $messageTransfer = null;

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
