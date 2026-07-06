<?php

namespace Syedmahamudul\MultiAi\Tests\Unit;

use Syedmahamudul\MultiAi\Providers\OpenAiProvider;
use Syedmahamudul\MultiAi\Exceptions\AiProviderException;
use Orchestra\Testbench\TestCase;

class OpenAiProviderTest extends TestCase
{
    protected OpenAiProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new OpenAiProvider([
            'api_key' => 'test-api-key',
            'model' => 'gpt-3.5-turbo',
        ]);
    }

    /** @test */
    public function it_can_set_api_key()
    {
        $this->provider->setApiKey('new-api-key');
        $this->assertInstanceOf(OpenAiProvider::class, $this->provider);
    }

    /** @test */
    public function it_can_set_model()
    {
        $this->provider->setModel('gpt-4');
        $this->assertEquals('gpt-4', $this->provider->getAvailableModels()[3]);
    }

    /** @test */
    public function it_returns_available_models()
    {
        $models = $this->provider->getAvailableModels();
        $this->assertIsArray($models);
        $this->assertContains('gpt-3.5-turbo', $models);
        $this->assertContains('gpt-4', $models);
    }

    /** @test */
    public function it_throws_exception_on_api_error()
    {
        $this->expectException(AiProviderException::class);
        $this->provider->generateText('Test prompt');
    }
}