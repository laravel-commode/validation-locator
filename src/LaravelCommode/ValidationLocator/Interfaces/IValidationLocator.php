<?php namespace LaravelCommode\ValidationLocator\Interfaces;

    use LaravelCommode\ValidationLocator\Validators\Validator;
    use Symfony\Component\Translation\TranslatorInterface;

    interface IValidationLocator
    {
        public function __construct(TranslatorInterface $translator);
        public function addValidator($shortName, $className);
        /**
         * @param $name
         * @return Validator
         * @throws \Exception
         */
        public function __get($name);
    }