<?php
require 'vendor/autoload.php';

$config = new Spryker\ApiPlatform\Configuration\ApiPlatformConfig([
    '/data/src/Pyz',
    '/data/src/SprykerAcademy',
    '/data/vendor/spryker',
    '/data/vendor/spryker-shop',
    '/data/vendor/spryker-feature',
], '/tmp/api-generator', '/data/src/Generated/Api', ['storefront'], false);

$schemaFinder = new Spryker\ApiPlatform\Schema\Finder\SchemaFinder($config);
$loader = new Spryker\ApiPlatform\Schema\Loader\YamlSchemaLoader();
$parser = new Spryker\ApiPlatform\Schema\Parser\SchemaParser();

$resources = [];
foreach ($schemaFinder->findSchemaFiles('storefront') as $file) {
    $schema = $loader->load($file);
    $parsed = $parser->parse($schema, $file);
    $name = $parsed['name'] ?? 'unknown';
    $resources[$name] = ($resources[$name] ?? 0) + 1;
}

ksort($resources);
echo json_encode($resources, JSON_PRETTY_PRINT), PHP_EOL;
