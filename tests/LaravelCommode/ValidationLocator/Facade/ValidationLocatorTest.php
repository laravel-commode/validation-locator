<?php

namespace LaravelCommode\ValidationLocator\Facade;

use LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider;

class ValidationLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAccessor()
    {
        $method = new \ReflectionMethod(ValidationLocator::class, 'getFacadeAccessor');
        $method->setAccessible(true);
        $this->assertSame(ValidationLocatorServiceProvider::PROVIDES_LOCATOR, $method->invoke(null));
        $method->setAccessible(false);
    }
}
