<?php
    namespace LaravelCommode\ValidationLocator\Facade;
    use Illuminate\Support\Facades\Facade;

    /**
 * Created by PhpStorm.
 * User: madman
 * Date: 11/25/14
 * Time: 1:26 AM
 */
    class ValidationLocator extends Facade
    {
        protected static function getFacadeAccessor()
        {
            return 'commode.validation-locator';
        }
    } 