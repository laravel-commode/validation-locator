<?php
namespace LaravelCommode\ValidationLocator;

use Illuminate\Validation\DatabasePresenceVerifier;
use LaravelCommode\Common\GhostService\GhostService;
use LaravelCommode\SilentService\SilentService;
use LaravelCommode\ValidationLocator\Locator\ValidationLocator;

class ValidationLocatorServiceProvider extends SilentService
{
    const PROVIDES_LOCATOR = 'laravel-commode.validation-locator';
    const PROVIDES_LOCATOR_INTERFACE = 'LaravelCommode\ValidationLocator\Interfaces\IValidationLocator';

    protected function aliases()
    {
        return [
            'ValidationLocator' => 'LaravelCommode\ValidationLocator\Facade\ValidationLocator'
        ];
    }

    protected function uses()
    {
        return ['Illuminate\Translation\TranslationServiceProvider'];
    }

    public function provides()
    {
        return [self::PROVIDES_LOCATOR, self::PROVIDES_LOCATOR_INTERFACE];
    }

    public function launching()
    {
    }

    public function registering()
    {
        $this->app->singleton(self::PROVIDES_LOCATOR, function () {
            $presenceVerifier = new DatabasePresenceVerifier($this->app->make('db'));
            return new ValidationLocator($this->app->make('translator'), $presenceVerifier);
        });

        $this->app->singleton(self::PROVIDES_LOCATOR_INTERFACE, function () {
            return $this->app->make(self::PROVIDES_LOCATOR);
        });
    }
}
