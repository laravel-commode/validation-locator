#Commode: Validation locator

>**_laravel-commode/validation-locator_** is laravel validation helper that could make your validation process
more context reliable.

<br />
####Contents

+ <a href="#installing">Installing</a>
+ <a href="#installing">Create validators</a>
+ <a href="#installing">Register validators in ValidationLocator</a>

##<a name="service">Installing</a>

You can install ___laravel-commode/common___ from composer:

    user@userpc:/path/to/app$ composer require laravel-commode/validation-locator

To enable package you need to register ``LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider``.
Actually, there are two ways of registering ``LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider`` -
first one is common for all service providers: you can simply add it into app's config providers list:

    <?php
        return [
            // ... config code
            'providers' => [
                // ... providers
                \LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider::class
            ]
        ];
