<?php

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

        // TODO: Use MessageMapper through factory to map $messageTransfer to $messageEntity

        $messageEntity->save();

        // TODO: Use MessageMapper through factory to map $messageEntity to $messageTransfer and return it
        return new MessageTransfer(); // TODO: To be replaced with the $messageTransfer from the MessageMapper
    }
}
