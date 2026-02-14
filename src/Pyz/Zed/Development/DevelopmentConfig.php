<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Development;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Development\DevelopmentConfig as SprykerDevelopmentConfig;

class DevelopmentConfig extends SprykerDevelopmentConfig
{
    /**
     * @return string
     */
    public function getCodingStandard(): string
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'phpcs.xml';
    }

    /**
     * @return array<string>
     */
    public function getOrganizationPathMap(): array
    {
        return [
            'Spryker' => $this->getPathToCore(),
            'SprykerFeature' => $this->getPathToFeature(),
            'SprykerEco' => $this->getPathToEco(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getIdeAutoCompletionSourceDirectoryGlobPatterns(): array
    {
        $patterns = [
            APPLICATION_VENDOR_DIR . '/*/*/src/' => '*/*/',
        ];

        foreach ($this->get(KernelConstants::PROJECT_NAMESPACES, []) as $projectNamespace) {
            $patterns[APPLICATION_SOURCE_DIR . '/' . $projectNamespace . '/'] = '*/';
        }

        return $patterns;
    }
}
