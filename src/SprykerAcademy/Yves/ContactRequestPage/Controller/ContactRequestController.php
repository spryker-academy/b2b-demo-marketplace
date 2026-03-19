<?php

namespace SprykerAcademy\Yves\ContactRequestPage\Controller;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerAcademy\Yves\ContactRequestPage\ContactRequestPageFactory getFactory()
 */
class ContactRequestController extends AbstractController
{
    public function getAction(string $name)
    {
        $contactRequestCriteriaTransfer = null;
        // TODO: Instantiate ContactRequestCriteriaTransfer and set the message name

        $contactRequestResponseTransfer = null;
        // TODO: Use the ContactRequestClient which is accessible by using `$this->getFactory()`
        // to find a message by a ContactRequestCriteriaTransfer

        return $this->view(
            ['message' => $contactRequestResponseTransfer->getContactRequest()],
            [],
            '@ContactRequestPage/views/message/get.twig'
        );
    }
}
