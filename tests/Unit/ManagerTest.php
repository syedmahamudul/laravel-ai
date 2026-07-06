<?php

namespace Syedmahamudul\MultiAi\Tests\Unit;

use Syedmahamudul\MultiAi\Services\AiManager;
use Syedmahamudul\MultiAi\Providers\OpenAiProvider;
use Syedmahamudul\MultiAi\Exceptions\AiProviderException;
use Orchestra\Testbench\TestCase;

class ManagerTest extends TestCase
{
    protected AiManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new AiManager();
        
        $this->manager->extend('test-provider', function () {
            return new OpenAiProvider(['api_key' => 'test']);
        });
    }

    /** @test */
    public function it_can_register_a_provider()
    {
        $this->assertInstanceOf(AiManager::class, $this->manager);
    }

    /** @test */
    public function it_can_get_a_provider()
    {
        $provider = $this->manager->provider('test-provider');
        $this->assertInstanceOf(OpenAiProvider::class, $provider);
    }

    /** @test */
    public function it_throws_exception_for_unknown_provider()
    {
        $this->expectException(AiProviderException::class);
        $this->manager->provider('unknown-provider');
    }

    /** @test */
    public function it_can_set_default_provider()
    {
        $this->manager->setDefaultProvider('test-provider');
        $this->assertEquals('test-provider', $this->manager->getDefaultProvider());
    }
}