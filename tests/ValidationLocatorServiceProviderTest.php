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

        public function testRegistration()
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
                $this->assertTrue(in_array($provider, array_merge([CommodeCommonServiceProvider::class], $service->uses())));
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

            $service->register();
        }

        public function tearDown()
        {
            \Mockery::close();
            parent::tearDown();
        }
    }
