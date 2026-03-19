<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\ContactRequest\Persistence;

use Generated\Shared\Transfer\ContactRequestTransfer;
use Orm\Zed\ContactRequest\Persistence\PyzContactRequest;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerAcademy\Zed\ContactRequest\Persistence\ContactRequestPersistenceFactory getFactory()
 */
class ContactRequestEntityManager extends AbstractEntityManager implements ContactRequestEntityManagerInterface
{
    public function createContactRequest(ContactRequestTransfer $contactRequestTransfer): ContactRequestTransfer
    {
        $contactRequestEntity = new PyzContactRequest();

        $contactRequestEntity->fromArray($contactRequestTransfer->modifiedToArray());

        $contactRequestEntity->save();

        return $this->getFactory()->createContactRequestMapper()->mapEntityToContactRequestTransfer(
            $contactRequestEntity,
            new ContactRequestTransfer(),
        );
    }
}
