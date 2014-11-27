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
            $this->translator = $translator;
        }

        /**
         * Registers validator in locator instance.
         *
         * @param string $alias Validator alias.
         * @param string $className Validator valid classname.
         * @return $this
         */
        public function addValidator($alias, $className)
        {
            $this->validators[$alias] = $className;
        }

        /**
         * Returns validator by it's alias.
         *
         * If validator is registered under given alias
         * method will return new instance of validator,
         * otherwise it will throw an exception
         *
         * @param string $alias Validator alias.
         * @throws \Exception
         * @return Validator
         */
        public function getValidator($alias)
        {
            if (isset($this->validators[$alias]))
            {
                $validator = $this->validators[$alias];
                return new $validator($this->translator);
            }

            throw new \Exception("Unknown validator {$alias}");
        }

        /**
         * Returns validator by it's alias.
         *
         * Getter for alias container.
         *
         * @param $alias
         * @throws \Exception
         * @return Validator
         */
        public function __get($alias)
        {
            return $this->getValidator($alias);
        }

        /**
         * Returns currently used translator.
         * @return \Symfony\Component\Translation\TranslatorInterface
         */
        public function getTranslator()
        {
            return $this->translator;
        }

        /**
         * Sets translator.
         * @param TranslatorInterface $translator Translator based upon
         * \Symfony\Component\Translation\TranslatorInterface interface.
         *
         * @return $this
         */
        public function setTranslator(TranslatorInterface $translator)
        {
            $this->translator = $translator;
            return $this;
        }
    }