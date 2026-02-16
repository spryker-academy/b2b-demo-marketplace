<?php
require 'vendor/autoload.php';
$config = new Spryker\ApiPlatform\Configuration\ApiPlatformConfig([
    '/data/src/Pyz',
    '/data/src/SprykerAcademy/Glue',
    '/data/vendor/spryker',
    '/data/vendor/spryker-shop',
    '/data/vendor/spryker-feature',
], '/tmp/api-generator', '/data/src/Generated/Api', ['storefront'], false);
$finder = new Spryker\ApiPlatform\Schema\Finder\SchemaFinder($config);
foreach ($finder->findSchemaFiles('storefront') as $file) {
    echo $file->getRealPath(), PHP_EOL;
}
