<?php namespace LaravelCommode\ValidationLocator\Locator;

    use LaravelCommode\ValidationLocator\Interfaces;
    use LaravelCommode\ValidationLocator\Validators\Validator;
    use LaravelCommode\ValidationLocator\Interfaces\IValidationLocator;
    use Symfony\Component\Translation\TranslatorInterface;

    class ValidationLocator implements Interfaces\IValidationLocator
    {
        protected $validators = [];

        /**
         * @var
         */
        private $translator;

        public function __construct(TranslatorInterface $translator)
        {
            $this->translator = $translator;
        }

        public function addValidator($shortname, $className)
        {
            $this->validators[$shortname] = $className;
        }

        /**
         * @param $name
         * @return Validator
         * @throws \Exception
         */
        public function __get($name)
        {
            if (isset($this->validators[$name]))
            {
                $validator = $this->validators[$name];
                return new $validator($this->translator);
            }

            throw new \Exception("Unknown validator {$name}");
        }
    }