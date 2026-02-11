<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Persistence;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldPersistenceFactory getFactory()
 */
class HelloWorldRepository extends AbstractRepository implements HelloWorldRepositoryInterface
{
    public function findMessage(MessageCriteriaTransfer $messageCriteria): ?MessageTransfer
    {
        $messageEntity = null;
        // TODO: Get MessageQuery through factory and query one message and assign to $messageEntity
        // access factory: $this->getFactory() (see @method annotation)
        // example query: $query->filterByName('some-name')->findOne()

        // TODO: Return null if there is no message

        // TODO: Use MessageMapper through factory to map $messageEntity to $messageTransfer and return it
        return null; // TODO: To be replaced with the $messageTransfer from the MessageMapper
    }
}
