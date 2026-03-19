<?php

namespace SprykerAcademy\Zed\ContactRequest\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        // TODO: initialize the message DTO and set a name

        return $this->viewResponse([
            // TODO: pass the DTO to the view
            'contactRequestText' => 'Contact Request!',
        ]);
    }
}
