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

        // TODO: Fetch and assign a message to the response array which gets passed to the template
        // Hint-1: Use the factory which has access to the HelloWorldFacade which offers the method
        // to find a message which returns a MessageResponseTransfer
        // Hint-2: Make sure to return the message object from the MessageResponseTransfer
        $response['helloWorldMessage'] = null;

        return $response;
    }
}
