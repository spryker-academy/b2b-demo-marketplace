<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldEntityManagerInterface;

class MessageWriter
{
    protected HelloWorldEntityManagerInterface $helloWorldEntityManager;

    public function create(MessageTransfer $messageTransfer): MessageTransfer
    {
        // TODO: Use the helloWorldEntityManager to create an antelope
    }
}
