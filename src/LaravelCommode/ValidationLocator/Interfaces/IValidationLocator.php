<?php namespace LaravelCommode\ValidationLocator\Interfaces;

    use Illuminate\Foundation\Application;
    use LaravelCommode\ValidationLocator\Validators\Validator;
    use Symfony\Component\Translation\TranslatorInterface;

    interface IValidationLocator
    {
        public function __construct(TranslatorInterface $translator);

        public function addValidator($shortName, $className);

        public function getValidator($name);
        /**
         * @param $name
         * @return Validator
         * @throws \Exception
         */
        public function __get($name);

        /**
         * @return \Symfony\Component\Translation\TranslatorInterface
         */
        public function getTranslator();
    }