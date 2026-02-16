<?php
require_once '/data/vendor/autoload.php';

$dirs = ['/data/src/Generated/Api/Storefront'];
$classes = ApiPlatform\Metadata\Util\ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories($dirs);
$filtered = [];
foreach ($classes as $name => $reflectionClass) {
    if (str_starts_with($name, 'Generated\\Api\\Storefront\\')) {
        $filtered[$name] = $reflectionClass->getFileName();
    }
}
ksort($filtered);
echo json_encode($filtered, JSON_PRETTY_PRINT), PHP_EOL;
