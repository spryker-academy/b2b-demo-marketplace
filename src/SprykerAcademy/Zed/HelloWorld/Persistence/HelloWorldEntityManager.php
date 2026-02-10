<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Persistence;

use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\HelloWorld\Persistence\PyzMessage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldPersistenceFactory getFactory()
 */
class HelloWorldEntityManager extends AbstractEntityManager implements HelloWorldEntityManagerInterface
{
    public function createMessage(MessageTransfer $messageTransfer): MessageTransfer
    {
        $messageEntity = new PyzMessage();

        $messageEntity->fromArray($messageTransfer->modifiedToArray());

        $messageEntity->save();

        return $this->getFactory()->createMessageMapper()->mapEntityToMessageTransfer(
            $messageEntity,
            new MessageTransfer(),
        );
    }
}
