<?php namespace LaravelCommode\ValidationLocator\Locator;

    use LaravelCommode\ValidationLocator\Interfaces;
    use LaravelCommode\ValidationLocator\Validators\Validator;
    use LaravelCommode\ValidationLocator\Interfaces\IValidationLocator;
    use Symfony\Component\Translation\TranslatorInterface;

    class ValidationLocator implements Interfaces\IValidationLocator
    {
        protected $validators = [];

        /**
         * @var TranslatorInterface
         */
        private $translator;

        public function __construct(TranslatorInterface $translator)
        {
            $this->setTranslator($translator);
        }

        public function addValidator($shortname, $className)
        {
            $this->validators[$shortname] = $className;
        }

        /**
         * @param $name
         * @return mixed
         * @throws \Exception
         */
        public function getValidator($name)
        {
            if (isset($this->validators[$name]))
            {
                $validator = $this->validators[$name];
                return new $validator($this->translator);
            }

            throw new \Exception("Unknown validator {$name}");
        }

        /**
         * @param $name
         * @return \LaravelCommode\ValidationLocator\Validators\Validator
         */
        public function __get($name)
        {
            return $this->getValidator($name);
        }

        /**
         * @return \Symfony\Component\Translation\TranslatorInterface
         */
        public function getTranslator()
        {
            return $this->translator;
        }

        /**
         * @param \Symfony\Component\Translation\TranslatorInterface $translator
         */
        public function setTranslator(TranslatorInterface $translator)
        {
            $this->translator = $translator;
        }
    }