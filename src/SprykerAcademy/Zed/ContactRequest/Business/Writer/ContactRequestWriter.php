<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\ContactRequest\Business\Writer;

use Generated\Shared\Transfer\ContactRequestTransfer;
use SprykerAcademy\Zed\ContactRequest\Persistence\ContactRequestEntityManagerInterface;

class ContactRequestWriter
{
    public function __construct(protected ContactRequestEntityManagerInterface $contactRequestEntityManager)
    {
    }

    public function create(ContactRequestTransfer $contactRequestTransfer): ContactRequestTransfer
    {
        return $this->contactRequestEntityManager->createContactRequest($contactRequestTransfer);
    }
}
