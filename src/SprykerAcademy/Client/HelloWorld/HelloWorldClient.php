<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Client\HelloWorld;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * {@inheritDoc}
 *
 * @api
 *
 * @method \SprykerAcademy\Client\HelloWorld\HelloWorldFactory getFactory()
 */
class HelloWorldClient extends AbstractClient implements HelloWorldClientInterface
{
    public function findMessage(MessageCriteriaTransfer $messageCriteria): MessageResponseTransfer
    {
        // TODO: Use the factory to create a HelloWorldStub and use it to find a message
        // Hint: You can access the HelloWorldFactory through $this->getFactory()
    }
}
