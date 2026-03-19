<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerAcademy\Zed\ContactRequest\Communication\Controller;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerAcademy\Zed\ContactRequest\Business\ContactRequestFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly ContactRequestFacadeInterface $contactRequestFacade,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        $contactRequestTransfer = new ContactRequestTransfer();
        $contactRequestTransfer->setMessage('Contact Request!');
        $contactRequestTransfer->setIdContactRequest(1);

        return $this->viewResponse([
            'contactRequest' => $contactRequestTransfer,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function addAction(Request $request): array
    {
        $message = $request->query->get('message', 'test');

        $contactRequestCriteriaTransfer = new ContactRequestCriteriaTransfer();
        $contactRequestCriteriaTransfer->setMessage($message);

        $contactRequestResponseTransfer = $this->contactRequestFacade
            ->findContactRequest($contactRequestCriteriaTransfer);

        $contactRequestTransfer = $contactRequestResponseTransfer->getContactRequest();

        if (!$contactRequestTransfer) {
            $contactRequestTransfer = new ContactRequestTransfer();
            $contactRequestTransfer->setMessage($message);

            $contactRequestTransfer = $this->contactRequestFacade->createContactRequest($contactRequestTransfer);
        }

        return $this->viewResponse([
            'message' => $contactRequestTransfer,
        ]);
    }
}
