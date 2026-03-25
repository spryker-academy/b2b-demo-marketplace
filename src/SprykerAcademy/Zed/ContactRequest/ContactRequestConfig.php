<?php

declare(strict_types = 1);

namespace SprykerAcademy\Zed\ContactRequest;

use SprykerAcademy\Shared\ContactRequest\ContactRequestConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ContactRequestConfig extends AbstractBundleConfig
{
    public function getMyConfigValue(): string
    {
        return $this->get(ContactRequestConstants::MY_CONFIG_VALUE, 'This is a default value');
    }
}
