<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerAcademy\Zed\HelloWorld\Communication\Controller;

use Pyz\Shared\AiFoundation\AiPromptHelperTrait;
use Spryker\Client\AiFoundation\AiFoundationClientInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HelloController extends AbstractController
{
    use AiPromptHelperTrait;

    private const string DEFAULT_USER_MESSAGE = 'Hello AI! My name is Hidran. What do you know of me';

    public function __construct(private readonly AiFoundationClientInterface $aiFoundationClient)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function indexAction(Request $request): array
    {
        $userInput = (string)$request->get('message', static::DEFAULT_USER_MESSAGE);

        $responseContent = $this->promptText($userInput);

        return $this->viewResponse([
            'helloWorldText' => $responseContent,
        ]);
    }

}
