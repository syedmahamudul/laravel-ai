<?php

namespace Syedmahamudul\LaravelAi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Syedmahamudul\LaravelAi\Contracts\AiProviderInterface provider(string|null $name = null)
 * @method static string generateText(string $prompt, array $options = [])
 * @method static string chat(array $messages, array $options = [])
 * @method static array embeddings(string $text, array $options = [])
 * @method static \Syedmahamudul\LaravelAi\Contracts\AiProviderInterface setDefaultProvider(string $name)
 * @method static string getDefaultProvider()
 * @method static array getProviders()
 */
class MultiAi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'multi-ai';
    }
}