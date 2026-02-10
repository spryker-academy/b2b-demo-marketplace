<?php

namespace SprykerAcademy\Zed\HelloWorld\Business;

use SprykerAcademy\Zed\HelloWorld\Business\Reader\MessageReader;
use SprykerAcademy\Zed\HelloWorld\Business\Writer\MessageWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldEntityManagerInterface getEntityManager()
 * @method \SprykerAcademy\Zed\HelloWorld\Persistence\HelloWorldRepositoryInterface getRepository()
 */
class HelloWorldBusinessFactory extends AbstractBusinessFactory
{
    public function createMessageWriter(): MessageWriter
    {
        return new MessageWriter(
            $this->getEntityManager()
        );
    }

    public function createMessageReader(): MessageReader
    {
        return new MessageReader(
            $this->getRepository()
        );
    }
}
