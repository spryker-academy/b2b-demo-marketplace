<?php

declare(strict_types = 1);

namespace Pyz\Yves\CustomerPage\Form\Transformer;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use SprykerAcademy\Client\HelloWorld\HelloWorldClientInterface;
use Symfony\Component\Form\DataTransformerInterface;

class MessageTransformer implements DataTransformerInterface
{
    protected HelloWorldClientInterface $helloWorldClient;

    // TODO: Make HelloWorldClient available through the constructor

    public function transform($value)
    {
        if (!$value) {
            return '';
        }

        // TODO-1: Use the HelloWorldClient to find a message
        // Hint: The `value` is the ID of the message
        $messageResponseTransfer = new MessageResponseTransfer();

        if (!$messageResponseTransfer->getMessage()) {
            return '';
        }

        return $messageResponseTransfer->getMessage()->getMessage();
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        // TODO-2: Use the HelloWorldClient to find a message
        // Hint: The `value` is the message text
        $messageResponseTransfer = new MessageResponseTransfer();

        if ($messageResponseTransfer->getMessage()) {
            // TODO-3: Return the message id
            return null;
        }

        // TODO-4: Use the HelloWorldClient to create a message and return the message's id
        return null;
    }
}
