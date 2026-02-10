<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
    public function addAction(Request $request): array
    {
        $message = $request->query->get('message', 'Oskar');
        $messageCriteriaTransfer = new MessageCriteriaTransfer();
        $messageCriteriaTransfer->setMessage($message);

        $messageResponseTransfer = $this->getFacade()
            ->findMessage($messageCriteriaTransfer);

        $messageTransfer = $messageResponseTransfer->getMessage();

        if (!$messageTransfer) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage($message);

            $messageTransfer = $this->getFacade()->createMessage($messageTransfer);
        }

        return $this->viewResponse([
            'message' => $messageTransfer,
        ]);
    }
}
