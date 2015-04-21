<?php
    namespace Validators;
    use Illuminate\Validation\PresenceVerifierInterface;
    use LaravelCommode\ValidationLocator\Validators\Validator;
    use Symfony\Component\Translation\TranslatorInterface;

    /**
     * Created by PhpStorm.
     * User: madman
     * Date: 11/24/14
     * Time: 5:32 PM
     */
    class ValidatorTest extends \PHPUnit_Framework_TestCase
    {
        protected function translatorMock()
        {
            return \Mockery::mock('Symfony\Component\Translation\TranslatorInterface');
        }

        protected function presenceMock()
        {
            return \Mockery::mock('Illuminate\Validation\PresenceVerifierInterface');
        }

        protected function validatorMock(TranslatorInterface $translator, PresenceVerifierInterface $presenceVerifier)
        {
            return $this->getMockBuilder('LaravelCommode\ValidationLocator\Validators\Validator')->
                setConstructorArgs(func_get_args())->
                setMethods(['getRules', 'getMessages'])->
                getMockForAbstractClass();
        }

        protected function buildValidator($translatorMock, $presenceMock, array $rules = [], array $messages = [])
        {
            $validatorMock = $this->validatorMock($translatorMock, $presenceMock);

            $validatorMock->expects($this->any())->method('getRules')->will(
                $this->returnValue($rules)
            );

            $validatorMock->expects($this->any())->method('getMessages')->will(
                $this->returnValue($messages)
            );

            return $validatorMock;
        }

        protected function assertExceptionMessageEmptyData(\Exception $e)
        {
            $this->assertSame('No model was set. Nothing to validate', $e->getMessage());
        }

        public function testValidator__construct()
        {
            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();

            $validatorMock = $this->validatorMock($translatorMock, $presenceMock);

            $this->assertSame($translatorMock, $validatorMock->getTranslator());
        }

        public function testValidatorGetSetTranslator()
        {
            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();
            $validatorMock = $this->validatorMock($translatorMock, $presenceMock);

            $validatorMock->setTranslator($translatorMock);
            $this->assertSame($validatorMock->getTranslator(), $translatorMock);
        }

        public function testEmptyValidator()
        {
            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();
            $validatorMock = $this->validatorMock($translatorMock, $presenceMock);

            $this->assertNull($validatorMock->getValidator());
        }

        public function testSetModelValidatorPasses()
        {
            $modelRules = ['name' => 'required'];

            $model = ['name' => 'Foo'];

            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();
            $validatorMock = $this->buildValidator($translatorMock, $presenceMock, $modelRules);

            $validatorMock->setModel($model);

            /**
             * @var \Illuminate\Validation\Validator $validator
             */
            $validator = $validatorMock->getValidator();

            $this->assertTrue($validator instanceof \Illuminate\Validation\Validator);
            $this->assertTrue($validator->passes());
        }

        public function testSetModelValidatorFails()
        {
            $modelRules = ['name' => 'required', 'login' => 'required'];

            $model = ['name' => 'Foo'];

            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();

            $translatorMock->shouldReceive('trans')->twice(2)->andReturnUsing(function($field)
            {
                $this->assertTrue(in_array($field, ['validation.custom.login.required', "validation.attributes.login"]));
            });

            $validatorMock = $this->buildValidator($translatorMock, $presenceMock, $modelRules);

            $validatorMock->setModel($model);

            /**
             * @var \Illuminate\Validation\Validator $validator
             */
            $validator = $validatorMock->getValidator();

            $this->assertTrue($validator instanceof \Illuminate\Validation\Validator);
            $this->assertFalse($validator->passes());
        }

        public function testValidatorGetRules()
        {
            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();
            $validatorMock = $this->buildValidator($translatorMock, $presenceMock);

            $this->assertEmpty($validatorMock->getRules());

            $validatorMock = $this->buildValidator($translatorMock, $presenceMock, ['name' => 'required']);

            $this->assertNotEmpty($validatorMock->getRules());
        }

        public function testValidatorGetMesages()
        {
            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();

            $validatorMock = $this->buildValidator($translatorMock, $presenceMock);

            $this->assertEmpty($validatorMock->getMessages());

            $validatorMock = $this->buildValidator($translatorMock, $presenceMock, [], ['name.required' => 'Required!']);

            $this->assertNotEmpty($validatorMock->getMessages());
        }

        public function testValidatorPassesExceptionOnEmpty()
        {
            $modelRules = ['name' => 'required', 'login' => 'required'];

            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();
            $validatorMock = $this->buildValidator($translatorMock, $presenceMock, $modelRules);

            try {
                $validatorMock->passes();
            } catch(\Exception $e) {
                $this->assertExceptionMessageEmptyData($e);
            }
        }

        public function testValidatorFailsExceptionOnEmpty()
        {
            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();

            $validatorMock = $this->buildValidator($translatorMock, $presenceMock);

            try {
                $validatorMock->fails();
            } catch(\Exception $e) {
                $this->assertExceptionMessageEmptyData($e);
            }
        }

        public function testValidatorPassesAndPassesModel()
        {
            $modelRules = ['name' => 'required', 'login' => 'required'];

            $model = ['name' => 'Foo'];

            $translatorMock = $this->translatorMock();
            $translatorMock->shouldReceive('trans')->once();

            $presenceMock = $this->presenceMock();

            $validatorMock = $this->buildValidator($translatorMock, $presenceMock, $modelRules);

            $validatorMock->setModel($model);

            $this->assertFalse($validatorMock->passes());
            $this->assertFalse($validatorMock->passesModel($model));
        }

        public function testValidatorFailsAndFailsModel()
        {
            $modelRules = ['name' => 'required', 'login' => 'required'];

            $translatorMock = $this->translatorMock();
            $presenceMock = $this->presenceMock();
            $translatorMock->shouldReceive('trans')->twice();

            $validatorMock = $this->buildValidator($translatorMock, $presenceMock, $modelRules);

            $validatorMock->setModel([]);

            $this->assertTrue($validatorMock->fails());
            $this->assertTrue($validatorMock->failsModel([]));
        }
    } 