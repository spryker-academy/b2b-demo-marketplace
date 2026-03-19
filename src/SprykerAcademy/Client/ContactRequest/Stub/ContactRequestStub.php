<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Client\ContactRequest\Stub;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestResponseTransfer;
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

        // TODO: Fill in the right path for '/module-name/controller-name/action-name'
        // Hint: We want to call the src/SprykerAcademy/Zed/ContactRequest/Communication/Controller/GatewayController.php::findMessageAction()

        $contactRequestResponseTransfer = $this->zedRequestClient->call('/module-name/controller-name/action-name', $contactRequestCriteria);

        return $contactRequestResponseTransfer;
    }
}
