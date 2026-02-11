<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\HelloWorld\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerAcademy\Zed\HelloWorld\Business\Reader\MessageReader;
use SprykerAcademy\Zed\HelloWorld\Business\Writer\MessageWriter;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldEntityManagerInterface getEntityManager()
 * @method \SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldRepositoryInterface getRepository()
 */
class HelloWorldBusinessFactory extends AbstractBusinessFactory
{
    public function createMessageWriter(): MessageWriter
    {
        // TODO: Instantiate the MessageWriter with the right dependency
        // Hint: You can access the MessageEntityManager through $this->getEntityManager()
    }

    public function createMessageReader(): MessageReader
    {
        // TODO: Instantiate the MessageReader with the right dependency
        // Hint: You can access the MessageEntityRepository through $this->getRepository()
    }
}
