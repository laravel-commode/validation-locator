<?php

namespace LaravelCommode\ValidationLocator\Locator;

use Illuminate\Validation\PresenceVerifierInterface;

use LaravelCommode\ValidationLocator\Interfaces\IValidationLocator;
use LaravelCommode\ValidationLocator\Validators\Validator;

use Symfony\Component\Translation\TranslatorInterface;

class ValidationLocator implements IValidationLocator
{
    protected $validators = [];

    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var PresenceVerifierInterface
     */
    private $presenceVerifier;

    public function __construct(TranslatorInterface $translator, PresenceVerifierInterface $presenceVerifier)
    {
        $this->translator = $translator;
        $this->presenceVerifier = $presenceVerifier;
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
     * @throws \UnexpectedValueException
     * @return Validator
     */
    public function getValidator($alias)
    {
        if (array_key_exists($alias, $this->validators)) {
            $validator = $this->validators[$alias];
            return new $validator($this->translator, $this->presenceVerifier);
        }

        throw new \UnexpectedValueException("Unknown validator {$alias}");
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

    /**
     * @return PresenceVerifierInterface
     */
    public function getPresenceVerifier()
    {
        return $this->presenceVerifier;
    }

    /**
     * @param PresenceVerifierInterface $presenceVerifier
     * @return $this
     */
    public function setPresenceVerifier(PresenceVerifierInterface $presenceVerifier)
    {
        $this->presenceVerifier = $presenceVerifier;
        return $this;
    }
}
