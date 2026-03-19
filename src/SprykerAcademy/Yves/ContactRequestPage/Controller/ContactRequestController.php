<?php

namespace SprykerAcademy\Yves\ContactRequestPage\Controller;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerAcademy\Yves\ContactRequestPage\ContactRequestPageFactory getFactory()
 */
class ContactRequestController extends AbstractController
{
    public function getAction(int $idMessage): View
    {
        $contactRequestCriteriaTransfer = new ContactRequestCriteriaTransfer();
        $contactRequestCriteriaTransfer->setIdContactRequest($idMessage);

        $contactRequestResponseTransfer = $this->getFactory()
            ->getContactRequestClient()
            ->findContactRequest($contactRequestCriteriaTransfer);

        return $this->view(
            ['message' => $contactRequestResponseTransfer->getContactRequest()],
            [],
            '@ContactRequestPage/views/message/get.twig'
        );
    }
}
