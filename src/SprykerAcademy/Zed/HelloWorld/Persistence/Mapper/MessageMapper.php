<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\HelloWorld\Persistence\Mapper;

use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\HelloWorld\Persistence\PyzMessage;

class MessageMapper
{
    /**
     * @param \Orm\Zed\HelloWorld\Persistence\PyzMessage $messageEntity
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     */
    public function mapEntityToMessageTransfer(
        PyzMessage $messageEntity,
        MessageTransfer $messageTransfer = new MessageTransfer(),
    ): MessageTransfer {
        return $messageTransfer->fromArray($messageEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     * @param \Orm\Zed\HelloWorld\Persistence\PyzMessage $messageEntity
     */
    public function mapMessageTransferToEntity(
        MessageTransfer $messageTransfer,
        PyzMessage $messageEntity = new PyzMessage(),
    ): PyzMessage {
        $messageEntity->fromArray($messageTransfer->modifiedToArray());
        $messageEntity->setNew($messageTransfer->getIdMessage() === null);

        return $messageEntity;
    }
}
