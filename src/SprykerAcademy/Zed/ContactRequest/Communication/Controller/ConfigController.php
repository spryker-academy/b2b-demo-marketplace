<?php

declare(strict_types=1);

namespace SprykerAcademy\Zed\ContactRequest\Communication\Controller;

use SprykerAcademy\Zed\ContactRequest\ContactRequestConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

class ConfigController extends AbstractController
{
    public function __construct(private readonly ContactRequestConfig $config)
    {
    }

    public function indexAction(): array
    {
        $configValue = $this->config->getMyConfigValue();

        return $this->viewResponse([
            'configValue' => $configValue,
        ]);
    }
}
