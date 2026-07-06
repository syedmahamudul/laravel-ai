<?php

namespace Syedmahamudul\LaravelAi\Providers;

use Syedmahamudul\LaravelAi\Contracts\AiProviderInterface;
use Syedmahamudul\LaravelAi\Exceptions\AiProviderException;
use GuzzleHttp\Client;

class DeepSeekProvider implements AiProviderInterface
{
    protected string $apiKey;
    protected string $model = 'deepseek-chat';
    protected ?array $lastResponse = null;
    protected Client $client;
    protected array $config = [];
    protected string $baseUrl = 'https://api.deepseek.com/v1';

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->model = $config['model'] ?? 'deepseek-chat';
        $this->baseUrl = $config['base_url'] ?? 'https://api.deepseek.com/v1';
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
        ]);
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function generateText(string $prompt, array $options = []): string
    {
        $messages = [['role' => 'user', 'content' => $prompt]];
        return $this->chat($messages, $options);
    }

    public function chat(array $messages, array $options = []): string
    {
        try {
            $response = $this->client->post('/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $options['model'] ?? $this->model,
                    'messages' => $messages,
                    'max_tokens' => $options['max_tokens'] ?? 150,
                    'temperature' => $options['temperature'] ?? 0.7,
                    'top_p' => $options['top_p'] ?? 1.0,
                    'frequency_penalty' => $options['frequency_penalty'] ?? 0,
                    'presence_penalty' => $options['presence_penalty'] ?? 0,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $this->lastResponse = $data;
            return $data['choices'][0]['message']['content'] ?? '';
        } catch (\Exception $e) {
            throw AiProviderException::apiError('DeepSeek', $e->getMessage(), $e->getCode());
        }
    }

    public function embeddings(string $text, array $options = []): array
    {
        try {
            $response = $this->client->post('/embeddings', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $options['model'] ?? 'deepseek-embedding',
                    'input' => $text,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $this->lastResponse = $data;
            return $data['data'][0]['embedding'] ?? [];
        } catch (\Exception $e) {
            throw AiProviderException::apiError('DeepSeek', $e->getMessage(), $e->getCode());
        }
    }

    public function getLastRawResponse(): ?array
    {
        return $this->lastResponse;
    }

    public function getAvailableModels(): array
    {
        return [
            'deepseek-chat',
            'deepseek-coder',
            'deepseek-embedding',
        ];
    }
}