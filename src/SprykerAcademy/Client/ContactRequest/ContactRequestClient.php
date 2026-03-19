<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Client\ContactRequest;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestResponseTransfer;
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
}
