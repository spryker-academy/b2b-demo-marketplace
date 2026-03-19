<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerAcademy\Zed\ContactRequest\Business;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestResponseTransfer;
use Generated\Shared\Transfer\ContactRequestTransfer;

interface ContactRequestFacadeInterface
{
    /**
     * Specification:
     * - Creates and persists message
     * - Returns message with assigned ID
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContactRequestTransfer $contactRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ContactRequestTransfer
     */
    public function createContactRequest(ContactRequestTransfer $contactRequestTransfer): ContactRequestTransfer;

    /**
     * Specification:
     * - Finds message by defined criteria
     * - Returns a response-transfer which can hold a message if one is found
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContactRequestCriteriaTransfer $contactRequestCriteria
     *
     * @return \Generated\Shared\Transfer\ContactRequestResponseTransfer
     */
    public function findContactRequest(ContactRequestCriteriaTransfer $contactRequestCriteria): ContactRequestResponseTransfer;
}
