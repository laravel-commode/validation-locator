<?php
namespace LaravelCommode\ValidationLocator\Facade;

use Illuminate\Support\Facades\Facade;
use LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider;

class ValidationLocator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ValidationLocatorServiceProvider::PROVIDES_LOCATOR;
    }
}
