<?php

namespace SprykerAcademy\Zed\HelloWorld\Persistence;

use Generated\Shared\Transfer\MessageTransfer;

interface HelloWorldEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function createMessage(MessageTransfer $messageTransfer): MessageTransfer;
}
