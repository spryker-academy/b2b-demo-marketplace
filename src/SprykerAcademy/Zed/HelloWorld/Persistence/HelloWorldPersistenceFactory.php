<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\HelloWorld\Persistence;

use Orm\Zed\HelloWorld\Persistence\PyzMessageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerAcademy\Zed\HelloWorld\Persistence\Mapper\MessageMapper;

class HelloWorldPersistenceFactory extends AbstractPersistenceFactory
{
    public function createMessageQuery(): PyzMessageQuery
    {
        return PyzMessageQuery::create();
    }

    public function createMessageMapper(): MessageMapper
    {
        return new MessageMapper();
    }
}
