<?php

namespace Syedmahamudul\LaravelAi;

use Illuminate\Support\ServiceProvider;
use Syedmahamudul\LaravelAi\Services\AiManager;
use Syedmahamudul\LaravelAi\Providers\OpenAiProvider;
use Syedmahamudul\LaravelAi\Providers\DeepSeekProvider;
use Syedmahamudul\LaravelAi\Providers\GeminiProvider;

class MultiAiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/ai.php',
            'ai'
        );

        $this->app->singleton('multi-ai', function ($app) {
            $manager = new AiManager();
            
            $manager->extend('openai', function () {
                $config = config('ai.providers.openai', []);
                $provider = new OpenAiProvider($config);
                if (isset($config['api_key'])) {
                    $provider->setApiKey($config['api_key']);
                }
                return $provider;
            });

            $manager->extend('deepseek', function () {
                $config = config('ai.providers.deepseek', []);
                $provider = new DeepSeekProvider($config);
                if (isset($config['api_key'])) {
                    $provider->setApiKey($config['api_key']);
                }
                return $provider;
            });

            $manager->extend('gemini', function () {
                $config = config('ai.providers.gemini', []);
                $provider = new GeminiProvider($config);
                if (isset($config['api_key'])) {
                    $provider->setApiKey($config['api_key']);
                }
                return $provider;
            });

            return $manager;
        });

        $this->app->alias('multi-ai', 'Syedmahamudul\LaravelAi\Facades\MultiAi');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/ai.php' => config_path('ai.php'),
        ], 'ai-config');
    }
}