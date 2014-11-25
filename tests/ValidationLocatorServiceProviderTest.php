<?php

    use LaravelCommode\Common\CommodeCommonServiceProvider;
    use LaravelCommode\Common\GhostService\GhostServices;
    use LaravelCommode\Common\Resolver\Resolver;
    use LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider;

    class ValidationLocatorServiceProviderTest extends \PHPUnit_Framework_TestCase
    {
        public function getApplicationMock()
        {
            return \Mockery::mock('Illuminate\Foundation\Application');
        }

        protected function buildServiceForRegistering()
        {
            $serviceManager = new GhostServices();

            $appMock = $this->getApplicationMock();

            $service = new ValidationLocatorServiceProvider($appMock);

            $resolver = new Resolver($appMock);

            $appMock->shouldReceive('bound')->andReturn(false, true);

            $appMock->shouldReceive('bindShared')->once();
            $appMock->shouldReceive('bind')->once();

            $appMock->shouldReceive('booting')->once();

            $appMock->shouldReceive('forceRegister')->andReturnUsing(function($provider) use ($service)
            {
                $this->assertTrue(in_array($provider, array_merge(['LaravelCommode\Common\CommodeCommonServiceProvider'], $service->uses())));
            });

            $appMock->shouldReceive('make')->andReturnUsing(function ($shortCut) use ($serviceManager, $resolver)
            {
                switch($shortCut)
                {
                    case \LaravelCommode\Common\Constants\ServiceShortCuts::GHOST_SERVICE:
                        return $serviceManager;
                    case \LaravelCommode\Common\Constants\ServiceShortCuts::RESOLVER_SERVICE:
                        return $resolver;
                }
            });

            return $service;
        }

        protected function buildService()
        {
            $service = new ValidationLocatorServiceProvider($this->getApplicationMock());
            return $service;
        }

        public function testUses()
        {
            $service = $this->buildService();
            $this->assertTrue(
                in_array('Illuminate\Translation\TranslationServiceProvider', $service->uses())
            );
        }

        public function testProvides()
        {
            $service = $this->buildService();
            $this->assertTrue(
                in_array('commode.validation-locator', $service->provides())
            );
        }

        public function testLaunching()
        {
            $service = $this->buildService();

            $reflection = new \ReflectionClass($service);

            $reflectionMethod = $reflection->getMethod('launching');

            $reflectionMethod->setAccessible(true);

            $this->assertNull($reflectionMethod->invoke($service));

            $reflectionMethod->setAccessible(false);
        }

        public function testBoot()
        {
            $serviceMockBuilder = $this->getMockBuilder(
                'LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider'
            );

            $serviceMockBuilder->setConstructorArgs([$this->getApplicationMock()]);
            $serviceMockBuilder->setMethods(['package']);
            $serviceMock = $serviceMockBuilder->getMock();

            $serviceMock->boot();
        }

        public function testRegistration()
        {
            $service = $this->buildServiceForRegistering();
            $service->register();
        }

        public function tearDown()
        {
            \Mockery::close();
            parent::tearDown();
        }
    }
