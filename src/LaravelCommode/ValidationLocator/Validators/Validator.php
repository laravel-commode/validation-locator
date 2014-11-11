<?php namespace LaravelCommode\ValidationLocator\Validators;

    use Symfony\Component\Translation\TranslatorInterface;

    abstract class Validator
    {
        /**
         * @var \Symfony\Component\Translation\TranslatorInterface
         */
        private $translator;

        private $data = [];

        /**
         * @var \Illuminate\Validation\Validator
         */
        private $validator;

        public function __construct(TranslatorInterface $translator)
        {
            $this->translator = $translator;
        }

        abstract public function getRules($isNew = true);
        abstract public function getMessages();

        public function setModel(array $data = [], $isNew = true)
        {
            $this->data = $data;
            $this->validator = app('validator')->make($this->data, $this->getRules($isNew), $this->getMessages());
            $this->sometimes($this->validator);
            return $this;
        }

        /**
         * @return \Illuminate\Validation\Validator
         */
        public function getValidator()
        {
            return $this->validator;
        }

        public function sometimes(\Illuminate\Validation\Validator $validator) { }
    }