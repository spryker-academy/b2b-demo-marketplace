<?php

declare(strict_types = 1);

namespace Pyz\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Spryker\Zed\Customer\Communication\Controller\ViewController as SprykerViewController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class ViewController extends SprykerViewController
{
    public function indexAction(Request $request)
    {
        $response = parent::indexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        if (!isset($response['customer']) || !$response['customer']->getFkMessage()) {
            return $response;
        }

        $messageCriteriaTransfer = new MessageCriteriaTransfer();
        $messageCriteriaTransfer->setIdMessage($response['customer']->getFkMessage());

        $response['helloWorldMessage'] = $this->getFactory()
            ->getHelloWorldFacade()
            ->findMessage($messageCriteriaTransfer)
            ->getMessage();

        return $response;
    }
}
