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
        return new MessageWriter(
            $this->getEntityManager(),
        );
    }

    public function createMessageReader(): MessageReader
    {
        return new MessageReader(
            $this->getRepository(),
        );
    }
}
