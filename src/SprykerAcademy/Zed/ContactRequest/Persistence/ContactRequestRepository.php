<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\ContactRequest\Persistence;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerAcademy\Zed\ContactRequest\Persistence\ContactRequestPersistenceFactory getFactory()
 */
class ContactRequestRepository extends AbstractRepository implements ContactRequestRepositoryInterface
{
    public function findContactRequest(ContactRequestCriteriaTransfer $contactRequestCriteria): ?ContactRequestTransfer
    {
        $query = $this->getFactory()->createContactRequestQuery();
        $contactRequestEntity = null;
        if ($contactRequestCriteria->getIdContactRequest()) {
            $contactRequestEntity = $query->findOneByIdContactRequest($contactRequestCriteria->getIdContactRequest());
        } elseif ($contactRequestCriteria->getMessage()) {
            $contactRequestEntity = $query->findOneByMessage($contactRequestCriteria->getMessage());
        }
        if (!$contactRequestEntity) {
            return null;
        }

        return $this->getFactory()->createContactRequestMapper()->mapEntityToContactRequestTransfer($contactRequestEntity, new ContactRequestTransfer());
    }
}
