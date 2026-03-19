<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\ContactRequest\Persistence\Mapper;

use Generated\Shared\Transfer\ContactRequestTransfer;
use Orm\Zed\ContactRequest\Persistence\PyzContactRequest;

class ContactRequestMapper
{
    /**
     * @param \Orm\Zed\ContactRequest\Persistence\PyzContactRequest $contactRequestEntity
     * @param \Generated\Shared\Transfer\ContactRequestTransfer $contactRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ContactRequestTransfer
     */
    public function mapEntityToContactRequestTransfer(
        PyzContactRequest $contactRequestEntity,
        ContactRequestTransfer $contactRequestTransfer = new ContactRequestTransfer(),
    ): ContactRequestTransfer {
        return $contactRequestTransfer->fromArray($contactRequestEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ContactRequestTransfer $contactRequestTransfer
     * @param \Orm\Zed\ContactRequest\Persistence\PyzContactRequest $contactRequestEntity
     *
     * @return \Orm\Zed\ContactRequest\Persistence\PyzContactRequest
     */
    public function mapContactRequestTransferToEntity(
        ContactRequestTransfer $contactRequestTransfer,
        PyzContactRequest $contactRequestEntity = new PyzContactRequest(),
    ): PyzContactRequest {
        $contactRequestEntity->fromArray($contactRequestTransfer->modifiedToArray());
        $contactRequestEntity->setNew($contactRequestTransfer->getIdContactRequest() === null);

        return $contactRequestEntity;
    }
}
