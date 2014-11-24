<?php
    namespace Locator;
    use LaravelCommode\ValidationLocator\Locator\ValidationLocator;
    use LaravelCommode\ValidationLocator\Validators\Validator;
    use Symfony\Component\Translation\TranslatorInterface;

    class TestValidator extends Validator
    {
        public function getRules($isNew = true)
        {
            return ['name' => 'required'];
        }

        public function getMessages()
        {
            return [];
        }
    }

    /**
     * Created by PhpStorm.
     * User: madman
     * Date: 11/24/14
     * Time: 6:33 PM
     */
    class ValidationLocatorTest extends \PHPUnit_Framework_TestCase
    {
        protected function translatorMock()
        {
            return \Mockery::mock(TranslatorInterface::class);
        }

        protected function validatorMock(TranslatorInterface $translator)
        {
            return $this->getMockBuilder(Validator::class)->
                setConstructorArgs(func_get_args())->
                setMethods(['getRules', 'getMessages'])->
                getMockForAbstractClass();
        }

        public function testConstructor()
        {
            $validationLocator = new ValidationLocator($translator = $this->translatorMock());

            $this->assertSame($validationLocator->getTranslator(), $translator);
        }

        public function testGetSetTranslator()
        {
            $validationLocator = new ValidationLocator($translator = $this->translatorMock());

            $this->assertSame($validationLocator->getTranslator(), $translator);

            $validationLocator->setTranslator($translator);

            $this->assertSame($validationLocator->getTranslator(), $translator);
        }

        public function testAddGetValidatorExisting()
        {
            $validationLocator = new ValidationLocator($translator = $this->translatorMock());

            $validationLocator->addValidator('test', TestValidator::class);

            $this->assertTrue($validationLocator->getValidator('test') instanceof TestValidator);
            $this->assertTrue($validationLocator->test instanceof TestValidator);
        }

        public function testAddGetValidatorNotExisting()
        {
            $validationLocator = new ValidationLocator($translator = $this->translatorMock());

            $validationLocator->addValidator('test', TestValidator::class);

            try {
                $validator = $validationLocator->getValidator('tesm');
            } catch(\Exception $e) {
                $this->assertSame('Unknown validator tesm', $e->getMessage());
            }
        }

    } 