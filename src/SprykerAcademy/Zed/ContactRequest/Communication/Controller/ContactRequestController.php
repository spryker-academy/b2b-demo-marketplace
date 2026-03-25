<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\ContactRequest\Communication\Controller;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerAcademy\Zed\ContactRequest\Business\ContactRequestFacadeInterface getFacade()
 */
class ContactRequestController extends AbstractController
{
    public function addAction(Request $request): array
    {
        $message = $request->query->get('message', 'Oskar');
        $contactRequestCriteriaTransfer = new ContactRequestCriteriaTransfer();
        $contactRequestCriteriaTransfer->setMessage($message);

        $contactRequestResponseTransfer = $this->getFacade()
            ->findContactRequest($contactRequestCriteriaTransfer);

        $contactRequestTransfer = $contactRequestResponseTransfer->getContactRequest();

        if (!$contactRequestTransfer) {
            $contactRequestTransfer = new ContactRequestTransfer();
            $contactRequestTransfer->setMessage($message);

            $contactRequestTransfer = $this->getFacade()->createContactRequest($contactRequestTransfer);
        }

        return $this->viewResponse([
            'message' => $contactRequestTransfer,
        ]);
    }
}
