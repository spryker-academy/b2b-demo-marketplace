<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerAcademy\Zed\ContactRequest\Communication\Controller;

use Generated\Shared\Transfer\ContactRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @return array<string, ContactRequestTransfer>
     */
    public function indexAction(): array
    {
        $contactRequestTransfer = new ContactRequestTransfer();
        $contactRequestTransfer->setMessage('Contact Request!');
        $contactRequestTransfer->setIdContactRequest(1);

        return $this->viewResponse([
            'contactRequest' => $contactRequestTransfer,
        ]);
    }
}
