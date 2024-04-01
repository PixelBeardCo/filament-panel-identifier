<?php

namespace PixelBeardCo\FilamentPanelIdentifier;

use Spatie\LaravelPackageTools\Package;

class FilamentPanelIdentifierServiceProvider extends \Spatie\LaravelPackageTools\PackageServiceProvider
{
    public static string $name = 'filament-panel-identifier';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews();
    }
}
