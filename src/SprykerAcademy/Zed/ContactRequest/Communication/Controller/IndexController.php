<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\ContactRequest\Communication\Controller;

use Generated\Shared\Transfer\ContactRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    /**
     * @return array<string, ContactRequestTransfer>
     */
    public function indexAction(Request $request): array
    {
        $message = $request->get('message', 'Hello Spryker');
        $id = $this->castId($request->get('id', 1));
        $contactRequestTransfer = new ContactRequestTransfer();
        $contactRequestTransfer->setMessage($message);
        $contactRequestTransfer->setIdContactRequest($id);

        return $this->viewResponse([
            'message' => $contactRequestTransfer,
        ]);
    }
}
