<?php

declare(strict_types = 1);

namespace Pyz\Yves\CustomerPage\Form\Transformer;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use SprykerAcademy\Client\HelloWorld\HelloWorldClientInterface;
use Symfony\Component\Form\DataTransformerInterface;

class MessageTransformer implements DataTransformerInterface
{
    protected HelloWorldClientInterface $helloWorldClient;

    public function __construct(HelloWorldClientInterface $helloWorldClient)
    {
        $this->helloWorldClient = $helloWorldClient;
    }

    public function transform($value)
    {
        if (!$value) {
            return '';
        }

        $messageResponseTransfer = $this->helloWorldClient
            ->findMessage((new MessageCriteriaTransfer())->setIdMessage($value));

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

        $messageResponseTransfer = $this->helloWorldClient
            ->findMessage((new MessageCriteriaTransfer())->setMessage($value));

        if ($messageResponseTransfer->getMessage()) {
            return $messageResponseTransfer->getMessage()->getIdMessage();
        }

        return $this->helloWorldClient
            ->createMessage((new MessageTransfer())->setMessage($value))
            ->getIdMessage();
    }
}
