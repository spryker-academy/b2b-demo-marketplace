<?php

namespace SprykerAcademy\Zed\HelloWorld\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \SprykerAcademy\Zed\HelloWorld\Communication\HelloWorldCommunicationFactory getFactory()
 */
class ConfigController extends AbstractController
{
    public function indexAction()
    {
        $configValue = $this->getFactory()
            ->getConfig()
            ->getMyConfigValue();

        return $this->viewResponse([
            'configValue' => $configValue,
        ]);
    }
}
