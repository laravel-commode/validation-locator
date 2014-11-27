<?php namespace LaravelCommode\ValidationLocator\Validators;

    use Symfony\Component\Translation\TranslatorInterface;
    use Illuminate\Foundation\Application;

    /**
     * Class Validator
     *
     * Validation builder class for making validation
     * more context reliable.
     *
     * @package LaravelCommode\ValidationLocator\Validators
     */
    abstract class Validator
    {
        /**
         * @var TranslatorInterface
         */
        private $translator;

        private $data = [];

        /**
         * @var \Illuminate\Validation\Validator|null
         */
        private $validator = null;

        abstract public function getRules($isNew = true);

        abstract public function getMessages();

        public function __construct(TranslatorInterface $translator)
        {
            $this->translator = $translator;
        }

        /**
         * Sets current model.
         *
         * Appends validator data model, runs sometimes closures.
         *
         * @param array $data Data model to be validated.
         * @param bool $isNew Flag that shows of model is new or being updated.
         *
         * @return $this
         */
        public function setModel(array $data = [], $isNew = true)
        {
            $this->data = $data;

            $this->validator = new \Illuminate\Validation\Validator(
                $this->translator, $this->data, $this->getRules($isNew), $this->getMessages()
            );

            $this->sometimes($this->validator, $isNew);

            return $this;
        }

        /**
         * Returns instance of laravel's validator for you could
         * have access to message bag and some other functions.
         *
         * @return \Illuminate\Validation\Validator|null
         */
        public function getValidator()
        {
            return $this->validator;
        }

        /**
         * Allows to bind laravel's validator instance "sometimes"
         * callbacks. "Is new" marker will be passed.
         *
         * @param \Illuminate\Validation\Validator $validator Laravel validator instance
         * @param bool $isNew Flag that shows of model is new or being updated.
         */
        public function sometimes(\Illuminate\Validation\Validator $validator, $isNew = true) { }

        /**
         * Returns currently used TranslatorInterface.
         * @return TranslatorInterface
         */
        public function getTranslator()
        {
            return $this->translator;
        }

        /**
         * Sets TranslatorInterface.
         * @param TranslatorInterface $translator
         * @return $this
         */
        public function setTranslator(TranslatorInterface $translator)
        {
            $this->translator = $translator;
            return $this;
        }

        /**
         * Returns true if data model passes validation.
         *
         * Method wraps laravel validator's method passes()
         *
         * @return bool
         * @throws \Exception
         */
        public function passes()
        {
            if (!($this->validator instanceof \Illuminate\Validation\Validator))
            {
                throw new \Exception('No model was set. Nothing to validate');
            }

            return $this->getValidator()->passes();
        }

        /**
         * Sets data as current data model and returns true if data model passes validation.
         *
         * Method wraps laravel validator's method passes()
         *
         * @param array $data
         * @param bool $isNew
         * @return mixed
         */
        public function passesModel(array $data, $isNew = true)
        {
            return $this->setModel($data, $isNew)->passes();
        }

        /**
         * Returns true if data model fails validation.
         *
         * Method wraps laravel validator's method fails()
         *
         * @return bool
         * @throws \Exception
         */
        public function fails()
        {
            if (!($this->validator instanceof \Illuminate\Validation\Validator))
            {
                throw new \Exception('No model was set. Nothing to validate');
            }

            return $this->getValidator()->fails();
        }

        /**
         * Sets data as current data model and returns true if data model fails validation.
         *
         * Method wraps laravel validator's method fails()
         *
         * @param array $data
         * @param bool $isNew
         * @return mixed
         */
        public function failsModel(array $data, $isNew = true)
        {
            return $this->setModel($data, $isNew)->fails();
        }
    }