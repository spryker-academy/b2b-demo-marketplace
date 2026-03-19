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

        // TODO: Use ContactRequestMapper through factory to map $contactRequestTransfer to $contactRequestEntity

        $contactRequestEntity->save();

        // TODO: Use ContactRequestMapper through factory to map $contactRequestEntity to $contactRequestTransfer and return it
        return new ContactRequestTransfer(); // TODO: To be replaced with the $contactRequestTransfer from the ContactRequestMapper
    }
}
