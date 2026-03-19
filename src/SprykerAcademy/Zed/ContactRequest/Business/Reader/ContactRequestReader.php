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
    protected ContactRequestRepositoryInterface $contactRequestRepository;

    public function findContactRequest(ContactRequestCriteriaTransfer $contactRequestCriteria): ContactRequestResponseTransfer
    {
        $contactRequestTransfer = null;// TODO: Use the ContactRequestRepository to find a message

        // TODO: Create and return ContactRequestResponseTransfer
        // and set the properties `isSuccessful` and `message` based on
        // the return value from the ContactRequestRepository
        // Hint: If no message is returned from the repository `isSuccessful` must be false
    }
}
