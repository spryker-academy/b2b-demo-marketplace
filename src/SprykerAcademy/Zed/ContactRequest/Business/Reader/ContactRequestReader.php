<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\ContactRequest\Business\Reader;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestResponseTransfer;
use SprykerAcademy\Zed\ContactRequest\Persistence\ContactRequestRepositoryInterface;

class ContactRequestReader
{
    public function __construct(protected ContactRequestRepositoryInterface $contactRequestRepository)
    {
    }

    public function findContactRequest(ContactRequestCriteriaTransfer $contactRequestCriteria): ContactRequestResponseTransfer
    {
        $contactRequestTransfer = $this->contactRequestRepository->findContactRequest($contactRequestCriteria);
        $contactRequestResponseTransfer = new ContactRequestResponseTransfer();

        if ($contactRequestTransfer === null) {
            $contactRequestResponseTransfer->setIsSuccessful(false);
        } else {
            $contactRequestResponseTransfer->setIsSuccessful(true);
            $contactRequestResponseTransfer->setContactRequest($contactRequestTransfer);
        }

        return $contactRequestResponseTransfer;
    }
}
