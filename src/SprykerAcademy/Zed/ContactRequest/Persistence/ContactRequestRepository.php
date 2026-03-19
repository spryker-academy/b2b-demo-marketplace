<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
        $contactRequestEntity = null;

        // TODO: Get the query from the factory
        // TODO: If the criteria has an idMessage, use findOneBy<ColumnName>() to find by ID
        // TODO: If the criteria has a message string, use filterBy<ColumnName>() with Criteria::LIKE as second parameter, then call findOne()
        // Hint: You will need to import Propel\Runtime\ActiveQuery\Criteria

        // TODO: Return null if no entity was found

        // TODO: Use the ContactRequestMapper from the factory to map the entity to a transfer and return it

        return null;
    }
}
