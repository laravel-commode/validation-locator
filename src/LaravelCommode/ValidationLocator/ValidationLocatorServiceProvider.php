<?php
    namespace LaravelCommode\ValidationLocator;

    use Illuminate\Validation\DatabasePresenceVerifier;
    use LaravelCommode\Common\GhostService\GhostService;
    use LaravelCommode\ValidationLocator\Locator\ValidationLocator;

    class ValidationLocatorServiceProvider extends GhostService
    {
        protected $aliases = [
            'ValidationLocator' => 'LaravelCommode\ValidationLocator\Facade\ValidationLocator'
        ];

        public function uses()
        {
            return ['Illuminate\Translation\TranslationServiceProvider'];
        }

        public function provides()
        {
            return ['commode.validation-locator'];
        }

        protected function launching() { }

        public function boot()
        {
            $this->package('commode/validation-locator');
        }

        protected function registering()
        {
            $this->app->bindShared('commode.validation-locator', function()
            {
                $presenceVerifier = new DatabasePresenceVerifier($this->app->make('db'));
                return new ValidationLocator($this->app->make('translator'), $presenceVerifier);
            });

            $this->app->bind('LaravelCommode\ValidationLocator\Interfaces\IValidationLocator', function()
            {
                return $this->app->make('commode.validation-locator');
            });
        }
    }
