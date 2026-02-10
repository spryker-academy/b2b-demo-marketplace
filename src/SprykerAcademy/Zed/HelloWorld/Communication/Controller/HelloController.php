<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HelloController extends AbstractController
{
    /**
     * @return array<string, MessageTransfer>
     */
    public function indexAction(Request $request): array
    {
        $message = $request->get('message', 'Hello Spryker');
        $id = $this->castId($request->get('id', 1));
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setMessage($message);
        $messageTransfer->setIdMessage($id);

        return $this->viewResponse([
            'message' => $messageTransfer,
        ]);
    }
}
