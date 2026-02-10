<?php

namespace Pyz\Shared\AiFoundation;

use Generated\Shared\Transfer\PromptMessageTransfer;
use Generated\Shared\Transfer\PromptRequestTransfer;
use Spryker\Client\AiFoundation\AiFoundationClientInterface;


trait AiPromptHelperTrait
{
    protected function promptText(string $userInput): string
    {
        $promptRequest = (new PromptRequestTransfer())
            ->setPromptMessage((new PromptMessageTransfer())->setContent($userInput));

        return (string)$this->getAiFoundationClient()
            ->prompt($promptRequest)
            ->getMessage()
            ?->getContent();
    }

    protected function getAiFoundationClient(): AiFoundationClientInterface
    {
        if (!isset($this->aiFoundationClient) || !$this->aiFoundationClient instanceof AiFoundationClientInterface) {
            throw new \LogicException(
                'Controller must have a promoted $aiFoundationClient: AiFoundationClientInterface.'
            );
        }

        return $this->aiFoundationClient;
    }
}
