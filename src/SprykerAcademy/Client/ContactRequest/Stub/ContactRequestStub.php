<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Client\ContactRequest\Stub;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestResponseTransfer;
use Generated\Shared\Transfer\ContactRequestTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class ContactRequestStub
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected ZedRequestClientInterface $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    public function findContactRequest(ContactRequestCriteriaTransfer $contactRequestCriteria): ContactRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ContactRequestResponseTransfer $contactRequestResponseTransfer */
        $contactRequestResponseTransfer = $this->zedRequestClient->call('/contact-request/gateway/find-contact-request', $contactRequestCriteria);

        return $contactRequestResponseTransfer;
    }

    public function createContactRequest(ContactRequestTransfer $contactRequestTransfer): ContactRequestTransfer
    {
        /** @var \Generated\Shared\Transfer\ContactRequestTransfer $contactRequestTransfer */
        $contactRequestTransfer = $this->zedRequestClient->call('/contact-request/gateway/create-contact-request', $contactRequestTransfer);

        return $contactRequestTransfer;
    }
}
