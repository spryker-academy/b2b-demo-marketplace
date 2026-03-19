<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\ContactRequest\Business\Writer;

use Generated\Shared\Transfer\ContactRequestTransfer;
use SprykerAcademy\Zed\ContactRequest\Persistence\ContactRequestEntityManagerInterface;

class ContactRequestWriter
{
    protected ContactRequestEntityManagerInterface $contactRequestEntityManager;

    public function create(ContactRequestTransfer $contactRequestTransfer): ContactRequestTransfer
    {
        // TODO: Use the contactRequestEntityManager to create an message
    }
}
