<?php

namespace SprykerAcademy\Zed\HelloWorld\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldEntityManagerInterface;

class MessageWriter
{
    protected HelloWorldEntityManagerInterface $helloWorldEntityManager;


    // TODO: Make MessageEntityManager available through the constructor

    public function create(MessageTransfer $messageTransfer): MessageTransfer
    {
        // TODO: Use the helloWorldEntityManager to create an antelope
    }
}
