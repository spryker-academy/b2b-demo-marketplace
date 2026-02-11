<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Business;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Business\HelloWorldBusinessFactory getFactory()
 */
class HelloWorldFacade extends AbstractFacade implements HelloWorldFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function createMessage(MessageTransfer $messageTransfer): MessageTransfer
    {
        // TODO: Use the factory to create a MessageWriter and use it to create a message
        // Hint: You can access the HelloWorldBusinessFactory through $this->getFactory()
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageCriteriaTransfer $messageCriteria
     *
     * @return \Generated\Shared\Transfer\MessageResponseTransfer
     */
    public function findMessage(MessageCriteriaTransfer $messageCriteria): MessageResponseTransfer
    {
        // TODO: Use the factory to create a MessageReader and use it to find a message
    }
}
