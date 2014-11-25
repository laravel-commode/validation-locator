<?php
    namespace Validators;
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

        protected function validatorMock(TranslatorInterface $translator)
        {
            return $this->getMockBuilder('LaravelCommode\ValidationLocator\Validators\Validator')->
                setConstructorArgs(func_get_args())->
                setMethods(['getRules', 'getMessages'])->
                getMockForAbstractClass();
        }

        protected function buildValidator($translatorMock, array $rules = [], array $messages = [])
        {
            $validatorMock = $this->validatorMock($translatorMock);

            $validatorMock->expects($this->any())->method('getRules')->will(
                $this->returnValue($rules)
            );

            $validatorMock->expects($this->any())->method('getMessages')->will(
                $this->returnValue($messages)
            );

            return $validatorMock;
        }

        public function testValidator__construct()
        {
            $translatorMock = $this->translatorMock();
            $validatorMock = $this->validatorMock($translatorMock);

            $this->assertSame($validatorMock->getTranslator(), $translatorMock);
        }

        public function testValidatorGetSetTranslator()
        {
            $translatorMock = $this->translatorMock();
            $validatorMock = $this->validatorMock($translatorMock);

            $validatorMock->setTranslator($translatorMock);
            $this->assertSame($validatorMock->getTranslator(), $translatorMock);
        }

        public function testEmptyValidator()
        {
            $translatorMock = $this->translatorMock();
            $validatorMock = $this->validatorMock($translatorMock);

            $this->assertNull($validatorMock->getValidator());
        }

        public function testSetModelValidatorPasses()
        {
            $modelRules = ['name' => 'required'];

            $model = ['name' => 'Foo'];

            $translatorMock = $this->translatorMock();
            $validatorMock = $this->buildValidator($translatorMock, $modelRules);

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

            $translatorMock->shouldReceive('trans')->twice(2)->andReturnUsing(function($field)
            {
                $this->assertTrue(in_array($field, ['validation.custom.login.required', "validation.attributes.login"]));
            });

            $validatorMock = $this->buildValidator($translatorMock, $modelRules);

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
            $validatorMock = $this->buildValidator($translatorMock);

            $this->assertEmpty($validatorMock->getRules());

            $validatorMock = $this->buildValidator($translatorMock, ['name' => 'required']);

            $this->assertNotEmpty($validatorMock->getRules());
        }

        public function testValidatorGetMesages()
        {
            $translatorMock = $this->translatorMock();

            $validatorMock = $this->buildValidator($translatorMock);

            $this->assertEmpty($validatorMock->getMessages());

            $validatorMock = $this->buildValidator($translatorMock, [], ['name.required' => 'Required!']);

            $this->assertNotEmpty($validatorMock->getMessages());
        }

        public function testValidatorPassesExceptionOnEmpty()
        {
            $modelRules = ['name' => 'required', 'login' => 'required'];

            $model = ['name' => 'Foo'];

            $translatorMock = $this->translatorMock();
            $validatorMock = $this->buildValidator($translatorMock, $modelRules);

            try {
                $validatorMock->passes();
            } catch(\Exception $e) {
                $this->assertSame('No model was set. Nothing to validate', $e->getMessage());
            }
        }

        public function testValidatorFailsExceptionOnEmpty()
        {
            $modelRules = ['name' => 'required', 'login' => 'required'];

            $model = ['name' => 'Foo'];

            $translatorMock = $this->translatorMock();
            $validatorMock = $this->buildValidator($translatorMock, $modelRules);

            try {
                $validatorMock->fails();
            } catch(\Exception $e) {
                $this->assertSame('No model was set. Nothing to validate', $e->getMessage());
            }
        }

        public function testValidatorPassesAndPassesModel()
        {
            $modelRules = ['name' => 'required', 'login' => 'required'];

            $model = ['name' => 'Foo'];

            $translatorMock = $this->translatorMock();
            $translatorMock->shouldReceive('trans')->once();

            $validatorMock = $this->buildValidator($translatorMock, $modelRules);

            $validatorMock->setModel($model);

            $this->assertFalse($validatorMock->passes());
            $this->assertFalse($validatorMock->passesModel($model));
        }

        public function testValidatorFailsAndFailsModel()
        {
            $modelRules = ['name' => 'required', 'login' => 'required'];

            $model = ['name' => 'Foo'];

            $translatorMock = $this->translatorMock();
            $translatorMock->shouldReceive('trans')->once();

            $validatorMock = $this->buildValidator($translatorMock, $modelRules);

            $validatorMock->setModel($model);

            $this->assertTrue($validatorMock->fails());
            $this->assertTrue($validatorMock->failsModel($model));
        }
    } 