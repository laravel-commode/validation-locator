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

        /**
         * @param TranslatorInterface $translator
         * @return $this
         */
        public function setTranslator(TranslatorInterface $translator);
    }