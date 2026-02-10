<?php

namespace SprykerAcademy\Zed\HelloWorld\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldEntityManagerInterface;

class MessageWriter
{
    protected HelloWorldEntityManagerInterface $helloWorldEntityManager;

    public function __construct(HelloWorldEntityManagerInterface $helloWorldEntityManager)
    {
        $this->helloWorldEntityManager = $helloWorldEntityManager;
    }

    public function create(MessageTransfer $messageTransfer): MessageTransfer
    {
        return $this->helloWorldEntityManager->createMessage($messageTransfer);
    }
}
