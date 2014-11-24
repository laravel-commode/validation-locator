<?php namespace LaravelCommode\ValidationLocator\Validators;

    use Symfony\Component\Translation\TranslatorInterface;
    use Illuminate\Foundation\Application;

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
            $this->setTranslator($translator);
        }

        public function setModel(array $data = [], $isNew = true)
        {
            $this->data = $data;

            $this->validator = new \Illuminate\Validation\Validator(
                $this->translator, $this->data, $this->getRules($isNew), $this->getMessages()
            );

            $this->sometimes($this->validator);

            return $this;
        }

        /**
         * @return \Illuminate\Validation\Validator|null
         */
        public function getValidator()
        {
            return $this->validator;
        }

        public function sometimes(\Illuminate\Validation\Validator $validator) { }

        /**
         * @return TranslatorInterface
         */
        public function getTranslator()
        {
            return $this->translator;
        }

        /**
         * @param TranslatorInterface $translator
         * @return $this
         */
        public function setTranslator(TranslatorInterface $translator)
        {
            $this->translator = $translator;
            return $this;
        }
    }