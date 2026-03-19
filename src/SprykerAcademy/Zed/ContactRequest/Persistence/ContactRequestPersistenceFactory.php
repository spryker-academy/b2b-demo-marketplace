<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\ContactRequest\Persistence;

use Orm\Zed\ContactRequest\Persistence\PyzContactRequestQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerAcademy\Zed\ContactRequest\Persistence\Mapper\ContactRequestMapper;

class ContactRequestPersistenceFactory extends AbstractPersistenceFactory
{
    public function createContactRequestQuery(): PyzContactRequestQuery
    {
        return PyzContactRequestQuery::create();
    }

    /**
     * @return \SprykerAcademy\Zed\ContactRequest\Persistence\Mapper\ContactRequestMapper
     */
    public function createContactRequestMapper(): ContactRequestMapper
    {
        return new ContactRequestMapper();
    }
}
