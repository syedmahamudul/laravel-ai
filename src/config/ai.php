<?php

return [
    'default_provider' => env('AI_DEFAULT_PROVIDER', 'openai'),

    'providers' => [
        'openai' => [
            'class' => \Syedmahamudul\LaravelAi\Providers\OpenAiProvider::class,
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
        ],
        
        'deepseek' => [
            'class' => \Syedmahamudul\LaravelAi\Providers\DeepSeekProvider::class,
            'api_key' => env('DEEPSEEK_API_KEY'),
            'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
        ],
        
        'gemini' => [
            'class' => \Syedmahamudul\LaravelAi\Providers\GeminiProvider::class,
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-pro'),
        ],
    ],

    'defaults' => [
        'max_tokens' => env('AI_MAX_TOKENS', 150),
        'temperature' => env('AI_TEMPERATURE', 0.7),
        'top_p' => env('AI_TOP_P', 1.0),
    ],
];