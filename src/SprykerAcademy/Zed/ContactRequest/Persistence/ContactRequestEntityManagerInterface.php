<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\ContactRequest\Persistence;

use Generated\Shared\Transfer\ContactRequestTransfer;

interface ContactRequestEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContactRequestTransfer $contactRequestTransfer
     */
    public function createContactRequest(ContactRequestTransfer $contactRequestTransfer): ContactRequestTransfer;
}
