<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

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
        $query = $this->getFactory()->createMessageQuery();
        $messageEntity = null;
        if ($messageCriteria->getIdMessage()) {
            $messageEntity = $query->findOneByIdMessage($messageCriteria->getIdMessage());
        } elseif ($messageCriteria->getMessage()) {
            $messageEntity = $query->findOneByMessage($messageCriteria->getMessage());
        }
        if (!$messageEntity) {
            return null;
        }

        return $this->getFactory()->createMessageMapper()->mapEntityToMessageTransfer($messageEntity, new MessageTransfer());
    }
}
