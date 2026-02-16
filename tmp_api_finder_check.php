<?php
require 'vendor/autoload.php';

$finder = new Symfony\Component\Finder\Finder();
$finder
    ->directories()
    ->in('/data/src/SprykerAcademy')
    ->name('storefront')
    ->filter(static function (SplFileInfo $file): bool {
        $path = $file->getRelativePathname();

        return str_ends_with($path, '/resources/api/storefront');
    });

foreach ($finder as $dir) {
    echo $dir->getRealPath(), PHP_EOL;
}
