<?php namespace LaravelCommode\ValidationLocator\Interfaces;

    use Illuminate\Foundation\Application;
    use LaravelCommode\ValidationLocator\Validators\Validator;
    use Symfony\Component\Translation\TranslatorInterface;

    /**
     * Interface IValidationLocator
     *
     * Basic interface for ValidationLocator. It's realizations are
     * supposed to get/set LaravelCommode\ValidationLocator\Validators\Validator
     * associated with their shortcuts.
     *
     * @package LaravelCommode\ValidationLocator\Interfaces
     */
    interface IValidationLocator
    {
        /**
         * Registers validator in locator instance.
         *
         * @param string $alias Validator alias.
         * @param string $className Validator valid classname.
         * @return $this
         */
        public function addValidator($alias, $className);

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
        public function getValidator($alias);

        /**
         * Returns validator by it's alias.
         *
         * Getter for alias container.
         *
         * @return Validator
         * @param $alias
         * @throws \Exception
         */
        public function __get($alias);

        /**
         * Returns currently used translator.
         * @return \Symfony\Component\Translation\TranslatorInterface
         */
        public function getTranslator();

        /**
         * Sets translator.
         * @param TranslatorInterface $translator Translator based upon
         * \Symfony\Component\Translation\TranslatorInterface interface.
         *
         * @return $this
         */
        public function setTranslator(TranslatorInterface $translator);
    }