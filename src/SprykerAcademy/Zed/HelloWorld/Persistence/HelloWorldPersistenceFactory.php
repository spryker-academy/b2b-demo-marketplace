<?php

namespace SprykerAcademy\Zed\HelloWorld\Persistence;

use Orm\Zed\HelloWorld\Persistence\PyzMessageQuery;
use SprykerAcademy\Zed\HelloWorld\Persistence\Mapper\MessageMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class HelloWorldPersistenceFactory extends AbstractPersistenceFactory
{
    public function createMessageQuery(): PyzMessageQuery
    {
        // TODO
    }

    /**
     * @return \SprykerAcademy\Zed\HelloWorld\Persistence\Mapper\MessageMapper
     */
    public function createMessageMapper(): MessageMapper
    {
        return new MessageMapper();
    }
}
