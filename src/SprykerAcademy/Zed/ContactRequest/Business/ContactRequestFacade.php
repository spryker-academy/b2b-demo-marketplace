<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerAcademy\Zed\ContactRequest\Business;

use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestResponseTransfer;
use Generated\Shared\Transfer\ContactRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use SprykerAcademy\Zed\ContactRequest\Business\Reader\ContactRequestReader;
use SprykerAcademy\Zed\ContactRequest\Business\Writer\ContactRequestWriter;

class ContactRequestFacade extends AbstractFacade implements ContactRequestFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function createContactRequest(ContactRequestTransfer $contactRequestTransfer): ContactRequestTransfer
    {
        // TODO: Use getService(ContactRequestWriter::class) to get the writer and call create()
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findContactRequest(ContactRequestCriteriaTransfer $contactRequestCriteria): ContactRequestResponseTransfer
    {
        // TODO: Use getService(ContactRequestReader::class) to get the reader and call findContactRequest()
    }
}
