<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    */
    'default_provider' => env('MULTI_AI_DEFAULT', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | Available AI Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'openai' => [
            'class' => \Syedmahamudul\MultiAi\Providers\OpenAiProvider::class,
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
            'organization' => env('OPENAI_ORGANIZATION'),
            'timeout' => 30,
        ],
        
        'deepseek' => [
            'class' => \Syedmahamudul\MultiAi\Providers\DeepSeekProvider::class,
            'api_key' => env('DEEPSEEK_API_KEY'),
            'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
            'base_url' => env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com/v1'),
            'timeout' => 30,
        ],
        
        'gemini' => [
            'class' => \Syedmahamudul\MultiAi\Providers\GeminiProvider::class,
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-pro'),
            'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
            'timeout' => 30,
        ],

        /*
        |--------------------------------------------------------------------------
        | Additional Providers (Uncomment to enable)
        |--------------------------------------------------------------------------
        */
        // 'claude' => [
        //     'class' => \Syedmahamudul\MultiAi\Providers\ClaudeProvider::class,
        //     'api_key' => env('CLAUDE_API_KEY'),
        //     'model' => env('CLAUDE_MODEL', 'claude-3-opus-20240229'),
        // ],
        
        // 'cohere' => [
        //     'class' => \Syedmahamudul\MultiAi\Providers\CohereProvider::class,
        //     'api_key' => env('COHERE_API_KEY'),
        //     'model' => env('COHERE_MODEL', 'command'),
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('MULTI_AI_CACHE_ENABLED', false),
        'ttl' => env('MULTI_AI_CACHE_TTL', 3600),
        'prefix' => 'multi_ai_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('MULTI_AI_LOGGING_ENABLED', false),
        'channel' => env('MULTI_AI_LOG_CHANNEL', 'stack'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Options
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'max_tokens' => env('MULTI_AI_MAX_TOKENS', 150),
        'temperature' => env('MULTI_AI_TEMPERATURE', 0.7),
        'top_p' => env('MULTI_AI_TOP_P', 1.0),
        'frequency_penalty' => env('MULTI_AI_FREQUENCY_PENALTY', 0),
        'presence_penalty' => env('MULTI_AI_PRESENCE_PENALTY', 0),
    ],
];