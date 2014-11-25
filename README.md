#Commode: Validation locator

[![Build Status](https://travis-ci.org/laravel-commode/validation-locator.svg?branch=master)](https://travis-ci.org/laravel-commode/validation-locator)
[![Code Climate](https://codeclimate.com/github/laravel-commode/validation-locator/badges/gpa.svg)](https://codeclimate.com/github/laravel-commode/validation-locator)
[![Test Coverage](https://codeclimate.com/github/laravel-commode/validation-locator/badges/coverage.svg)](https://codeclimate.com/github/laravel-commode/validation-locator)

>**_laravel-commode/validation-locator_** is laravel validation helper that could make your validation process
more context reliable.

<br />
####Contents

+ <a href="#installing">Installing</a>
+ <a href="#validator">Create validators</a>
+ <a href="#locator">Register validators in ValidationLocator</a>
+ <a href="#usage">Usage</a>


##<a name="service">Installing</a>

You can install ___laravel-commode/validation-locator___ using composer:

    "require": {
        "laravel-commode/validation-locator": "dev-master"
    }

To enable package you need to register ``LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider``
service provider in your application config.

    <?php
        // ./yourLaravelApplication/app/config/app.php
        return [
            // ... config code
            'providers' => [
                // ... providers
                'LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider'
            ]
        ];
<hr />
##<a name="validator">Create validators</a>

This approach allows you to keep your validations organized by classes to be more context reliable and reusable.
To create validator you need to extend `LaravelCommode\ValidationLocator\Validators\Validator` and implement
to public methods: getRules() and getMessages(). Method getRules() will receive $isNew boolean argument, that
is used as flag if the data model is going to be created of updated, and return an array of validation rules
that you usually pass to `Validator` facade. Method getMessages() must return an array of messages. For e.g:

    <?php namespace MyApp\Meta\Validations;

        use LaravelCommode\ValidationLocator\Validators\Validator;

        class AccountValidation extends Validator
        {
            /**
            * Validated account model data, if it's flagged as new
            * password validation will be required.
            */
            public function getRules($isNew = true)
            {
                $rules = [
                    'login'     => 'required',
                    'email'     => 'required',
                    'password'  => 'required'
                ];

                if ($isNew)
                {
                    $rules['password'] .= '|confirmed';
                }

                return $rules;
            }

            public function getMessages()
            {
                return [];
            }
        }

Also validator provides you an ability to register validator's 'sometimes' callback. You can simply do it by
overriding ``sometimes()`` method:

        /**
         * Allows to bind laravel validator instance "sometimes"
         * callbacks. "Is new" marker will be passed.
         *
         * @param \Illuminate\Validation\Validator $validator Laravel validator instance
         * @param bool $isNew Flag that shows of model is new or being updated.
         */
        public function sometimes(\Illuminate\Validation\Validator $validator, $isNew = true)
        {
            //$validator->sometimes(); ...
        }
<hr />
##<a name="locator">Register validators in ValidationLocator</a>

ValidationLocator is a container for keeping and resolving your validators. It is available through
``ValidationLocator`` facade, or - if you are a facade hater you can find it registered in IoC container
through alias "commode.validation-locator" or can resolve
``LaravelCommode\ValidationLocator\Interfaces\IValidationLocator``, that is registered in IoC as singleton.

To register your validator you simply need to call ``ValidationLocator::addValidator($alias, $className)``. For
e.g:


    <?php namespace MyApp\ServiceProviders;

        use LaravelCommode\Common\GhostService;
        use LaravelCommode\ValidationLocator\Interfaces\IValidationLocator;

        class ValidationServiceProvider extends GhostService
        {
            public function uses()
            {
                return [
                    'LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider'
                ];
            }

            /**
            *   Will be triggered when the app is booting
            **/
            protected function launching() { }

            /**
            *   Triggered when service is being registered
            **/
            protected function registering()
            {
                $this->with('commode.validation-locator', function (IValidationLocator $locator)
                {
                    $locator->addValidator('accountManagement', 'MyApp\Meta\Validations\AccountValidation');
                });

                // or ValidationLocator::addValidator('accountManagement', 'MyApp\Meta\Validations\AccountValidation')
            }
        }