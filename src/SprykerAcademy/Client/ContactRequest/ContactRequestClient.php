<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Client\ContactRequest;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestResponseTransfer;
use Generated\Shared\Transfer\ContactRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * {@inheritDoc}
 *
 * @api
 *
 * @method \SprykerAcademy\Client\ContactRequest\ContactRequestFactory getFactory()
 */
class ContactRequestClient extends AbstractClient implements ContactRequestClientInterface
{
    public function findContactRequest(ContactRequestCriteriaTransfer $contactRequestCriteria): ContactRequestResponseTransfer
    {
        return $this->getFactory()->createContactRequestStub()->findContactRequest($contactRequestCriteria);
    }

    public function createContactRequest(ContactRequestTransfer $contactRequestTransfer): ContactRequestTransfer
    {
        return $this->getFactory()
            ->createContactRequestStub()
            ->createContactRequest($contactRequestTransfer);
    }
}
