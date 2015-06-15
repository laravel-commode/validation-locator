<?php
namespace LaravelCommode\ValidationLocator\Validators;

use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Validator as BaseValidator;

use LaravelCommode\ValidationLocator\TestSubject\TestSubject;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Illuminate\Database\ConnectionResolverInterface|Mock
     */
    private $dbMock;

    /**
     * @var Translator|Mock
     */
    private $translatorMock;

    /**
     * @var DatabasePresenceVerifier
     */
    private $presenceVerifierMock;

    /**
     * @var TestSubject
     */
    private $testInstance;

    protected function setUp()
    {
        $this->dbMock = $this->getMock('Illuminate\Database\ConnectionResolverInterface');
        $this->translatorMock = $this->getMock(Translator::class, [], [], '', false);
        $this->presenceVerifierMock = new DatabasePresenceVerifier($this->dbMock);
        $this->testInstance = new TestSubject($this->translatorMock, $this->presenceVerifierMock);
        parent::setUp();
    }

    public function testTranslator()
    {
        $this->assertSame($this->translatorMock, $this->testInstance->getTranslator());
        $this->testInstance->setTranslator($this->translatorMock);
        $this->assertSame($this->translatorMock, $this->testInstance->getTranslator());
    }

    public function testGetValidator()
    {
        $data = ['name' => uniqid()];
        $this->assertTrue($this->testInstance->setModel($data)->getValidator() instanceof BaseValidator);
    }

    public function testFailsPasses()
    {
        $data = ['name' => uniqid()];

        $this->assertTrue(!$this->testInstance->setModel($data)->fails());
        $this->assertTrue(!$this->testInstance->failsModel($data));

        $this->assertTrue($this->testInstance->setModel($data)->passes());
        $this->assertTrue($this->testInstance->passesModel($data));
    }

    public function testLogicExceptions()
    {
        $instance = new TestSubject($this->translatorMock, $this->presenceVerifierMock);

        try {
            $instance->fails();
        } catch (\LogicException $e) {
            $this->assertSame('No model was set. Nothing to validate.', $e->getMessage());
        }

        try {
            $instance->passes();
        } catch (\LogicException $e) {
            $this->assertSame('No model was set. Nothing to validate.', $e->getMessage());
        }
    }

    protected function tearDown()
    {
        unset($this->testInstance);
        unset($this->presenceVerifierMock);
        unset($this->translatorMock);
        unset($this->dbMock);
        parent::tearDown();
    }
}
