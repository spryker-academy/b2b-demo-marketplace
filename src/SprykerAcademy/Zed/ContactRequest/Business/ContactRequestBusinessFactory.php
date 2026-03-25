<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\ContactRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerAcademy\Zed\ContactRequest\Business\Reader\ContactRequestReader;
use SprykerAcademy\Zed\ContactRequest\Business\Writer\ContactRequestWriter;

/**
 * @method \SprykerAcademy\Zed\ContactRequest\Persistence\ContactRequestEntityManagerInterface getEntityManager()
 * @method \SprykerAcademy\Zed\ContactRequest\Persistence\ContactRequestRepositoryInterface getRepository()
 */
class ContactRequestBusinessFactory extends AbstractBusinessFactory
{
    public function createContactRequestWriter(): ContactRequestWriter
    {
        return new ContactRequestWriter(
            $this->getEntityManager(),
        );
    }

    public function createContactRequestReader(): ContactRequestReader
    {
        return new ContactRequestReader(
            $this->getRepository(),
        );
    }
}
