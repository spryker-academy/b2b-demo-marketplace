<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Persistence\Mapper;

use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\HelloWorld\Persistence\PyzMessage;

class MessageMapper
{
    /**
     * @param \Orm\Zed\HelloWorld\Persistence\PyzMessage $messageEntity
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function mapEntityToMessageTransfer(
        PyzMessage $messageEntity,
        MessageTransfer $messageTransfer,
    ): MessageTransfer {
        return $messageTransfer->fromArray($messageEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     * @param \Orm\Zed\HelloWorld\Persistence\PyzMessage $messageEntity
     *
     * @return \Orm\Zed\HelloWorld\Persistence\PyzMessage
     */
    public function mapMessageTransferToEntity(
        MessageTransfer $messageTransfer,
        PyzMessage $messageEntity,
    ): PyzMessage {
        $messageEntity->fromArray($messageTransfer->modifiedToArray());
        $messageEntity->setNew($messageTransfer->getIdMessage() === null);

        return $messageEntity;
    }
}
