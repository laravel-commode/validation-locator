<?php

namespace LaravelCommode\ValidationLocator\Locator;

use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;

use LaravelCommode\ValidationLocator\TestSubject\TestSubject;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class ValidationLocatorTest extends \PHPUnit_Framework_TestCase
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
     * @var ValidationLocator
     */
    private $testInstance;

    /**
     * @var string
     */
    private $testSubject;

    protected function setUp()
    {
        $this->dbMock = $this->getMock('Illuminate\Database\ConnectionResolverInterface');
        $this->translatorMock = $this->getMock(Translator::class, [], [], '', false);
        $this->presenceVerifierMock = new DatabasePresenceVerifier($this->dbMock);
        $this->testInstance = new ValidationLocator($this->translatorMock, $this->presenceVerifierMock);
        $this->testSubject = TestSubject::class;
        parent::setUp();
    }

    public function testConstruct()
    {
        $this->testInstance->setPresenceVerifier($this->presenceVerifierMock);
        $this->assertSame($this->presenceVerifierMock, $this->testInstance->getPresenceVerifier());
        $this->testInstance->setTranslator($this->translatorMock);
        $this->assertSame($this->translatorMock, $this->testInstance->getTranslator());
    }

    public function testAddGet()
    {
        $this->testInstance->addValidator('dummy', $this->testSubject);

        $this->assertTrue(
            $this->testInstance->__get('dummy') instanceof $this->testSubject
        );

        try {
            $this->testInstance->getValidator('non-existent');
        } catch (\UnexpectedValueException $e) {
            $this->assertSame("Unknown validator non-existent", $e->getMessage());
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
