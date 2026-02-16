<?php
require_once '/data/vendor/autoload.php';

$classes = [
    'Generated\\Api\\Storefront\\StoresStorefrontResource',
    'Generated\\Api\\Storefront\\SuppliersStorefrontResource',
];

foreach ($classes as $class) {
    if (!class_exists($class)) {
        echo $class . " CLASS_MISSING\n";
        continue;
    }

    $r = new ReflectionClass($class);
    $attrs = $r->getAttributes(ApiPlatform\Metadata\ApiResource::class, ReflectionAttribute::IS_INSTANCEOF);
    echo $class . ' attrs=' . count($attrs) . "\n";
}
