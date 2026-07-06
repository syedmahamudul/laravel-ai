<?php

namespace Syedmahamudul\LaravelAi\Exceptions;

use Exception;

class AiProviderException extends Exception
{
    public static function apiError(string $provider, string $message, int $code = 0): self
    {
        return new self("{$provider} API Error: {$message}", $code);
    }
    
    public static function invalidConfiguration(string $provider): self
    {
        return new self("Invalid configuration for provider: {$provider}");
    }
    
    public static function providerNotFound(string $provider): self
    {
        return new self("Provider not found: {$provider}");
    }
}