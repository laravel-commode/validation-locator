<?php
    namespace LaravelCommode\ValidationLocator;

    use Illuminate\Translation\TranslationServiceProvider;
    use LaravelCommode\Common\GhostService\GhostService;
    use LaravelCommode\ValidationLocator\Locator\ValidationLocator;

    class ValidationLocatorServiceProvider extends GhostService
    {
        public function uses()
        {
            return [TranslationServiceProvider::class];
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
            $this->app->bindShared('commode.validation-locator', function() {
                return new ValidationLocator($this->app['translator']);
            });

            $this->app->bind(Interfaces\IValidationLocator::class, function() {
                return $this->app['commode.validation-locator'];
            });
        }
    }
