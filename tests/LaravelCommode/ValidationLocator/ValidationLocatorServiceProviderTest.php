<?php
namespace LaravelCommode\ValidationLocator;

use Illuminate\Translation\Translator;
use LaravelCommode\ValidationLocator\Interfaces\IValidationLocator;
use LaravelCommode\ValidationLocator\Locator\ValidationLocator;
use PHPUnit_Framework_MockObject_MockObject as Mock;


class ValidationLocatorServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mock|\Illuminate\Foundation\Application
     */
    private $application;

    /**
     * @var ValidationLocatorServiceProvider
     */
    private $testInstance;

    /**
     * @var \Illuminate\Database\ConnectionResolverInterface|Mock
     */
    private $dbMock;

    /**
     * @var Translator|Mock
     */
    private $translatorMock;

    /**
     * @var ValidationLocator|IValidationLocator
     */
    private $testCreated;

    protected function setUp()
    {
        $this->application = $this->getMock(
            'Illuminate\Foundation\Application',
            ['singleton', 'make']
        );

        $this->testInstance = new ValidationLocatorServiceProvider($this->application);
        $this->dbMock = $this->getMock('Illuminate\Database\ConnectionResolverInterface');
        $this->translatorMock = $this->getMock(Translator::class, [], [], '', false);
        $this->testCreated;
        parent::setUp();
    }

    public function testRegistering()
    {
        $singletonChecks = function ($serviceName, $callable) {
            switch ($serviceName)
            {
                case ValidationLocatorServiceProvider::PROVIDES_LOCATOR:
                    $this->testCreated = $callable();
                    break;
                case ValidationLocatorServiceProvider::PROVIDES_LOCATOR_INTERFACE:
                    $callable();
                    break;
            }
        };

        $makeCallback = function ($makingOf) {
            switch($makingOf)
            {
                case 'db':
                    return $this->dbMock;
                case 'translator':
                    return $this->translatorMock;
                case 'laravel-commode.validation-locator':
                    return $this->testCreated;
            }
        };

        $this->application->expects($this->any())->method('singleton')
            ->will($this->returnCallback($singletonChecks));

        $this->application->expects($this->any())->method('make')
            ->will($this->returnCallback($makeCallback));

        $this->testInstance->registering();
    }

    public function testAliases()
    {
        $aliasMethod = new \ReflectionMethod($this->testInstance, 'aliases');

        $aliasMethod->setAccessible(true);

        $this->assertSame(
            ['ValidationLocator' => 'LaravelCommode\ValidationLocator\Facade\ValidationLocator'],
            $aliasMethod->invoke($this->testInstance)
        );

        $aliasMethod->setAccessible(false);
    }

    public function testUses()
    {
        $usesMethod = new \ReflectionMethod($this->testInstance, 'uses');

        $usesMethod->setAccessible(true);

        $this->assertSame(
            ['Illuminate\Translation\TranslationServiceProvider'],
            $usesMethod->invoke($this->testInstance)
        );

        $usesMethod->setAccessible(false);
    }

    public function testProvides()
    {
        $this->assertSame(
            [
                ValidationLocatorServiceProvider::PROVIDES_LOCATOR,
                ValidationLocatorServiceProvider::PROVIDES_LOCATOR_INTERFACE
            ],
            $this->testInstance->provides()
        );
    }

    public function testLaunching()
    {
        $this->testInstance->launching();
    }

    protected function tearDown()
    {
        unset($this->testInstance);
        unset($this->application);
        parent::tearDown();
    }
}
