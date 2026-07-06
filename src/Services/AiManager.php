<?php

namespace Syedmahamudul\LaravelAi\Services;

use Syedmahamudul\LaravelAi\Contracts\AiProviderInterface;
use Syedmahamudul\LaravelAi\Exceptions\AiProviderException;

class AiManager
{
    protected array $providers = [];
    protected ?string $defaultProvider = null;
    protected array $instances = [];

    public function extend(string $name, callable $factory): self
    {
        $this->providers[$name] = $factory;
        return $this;
    }

    public function provider(?string $name = null): AiProviderInterface
    {
        $name = $name ?: $this->getDefaultProvider();
        
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (!isset($this->providers[$name])) {
            $this->createProviderFromConfig($name);
        }

        if (is_callable($this->providers[$name])) {
            $this->instances[$name] = ($this->providers[$name])();
        }

        return $this->instances[$name];
    }

    protected function createProviderFromConfig(string $name): void
    {
        $config = config("ai.providers.{$name}");
        
        if (!$config || !isset($config['class'])) {
            throw AiProviderException::providerNotFound($name);
        }

        $this->extend($name, function () use ($config) {
            $class = $config['class'];
            $apiKey = $config['api_key'] ?? '';
            $provider = new $class($config);
            
            if (method_exists($provider, 'setApiKey')) {
                $provider->setApiKey($apiKey);
            }
            
            return $provider;
        });
    }

    public function setDefaultProvider(string $name): self
    {
        $this->defaultProvider = $name;
        return $this;
    }

    public function getDefaultProvider(): string
    {
        return $this->defaultProvider ?: config('ai.default_provider', 'openai');
    }

    public function getProviders(): array
    {
        return array_keys($this->providers);
    }

    public function __call($method, $parameters)
    {
        return $this->provider()->$method(...$parameters);
    }
}