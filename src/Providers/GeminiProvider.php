<?php

namespace Syedmahamudul\LaravelAi\Providers;

use Syedmahamudul\LaravelAi\Contracts\AiProviderInterface;
use Syedmahamudul\LaravelAi\Exceptions\AiProviderException;
use GuzzleHttp\Client;

class GeminiProvider implements AiProviderInterface
{
    protected string $apiKey;
    protected string $model = 'gemini-pro';
    protected ?array $lastResponse = null;
    protected Client $client;
    protected array $config = [];
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->model = $config['model'] ?? 'gemini-pro';
        $this->baseUrl = $config['base_url'] ?? 'https://generativelanguage.googleapis.com/v1beta';
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
        try {
            $response = $this->client->post("/models/{$this->model}:generateContent", [
                'query' => ['key' => $this->apiKey],
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => $options['max_tokens'] ?? 150,
                        'temperature' => $options['temperature'] ?? 0.7,
                        'topP' => $options['top_p'] ?? 1.0,
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $this->lastResponse = $data;
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        } catch (\Exception $e) {
            throw AiProviderException::apiError('Gemini', $e->getMessage(), $e->getCode());
        }
    }

    public function chat(array $messages, array $options = []): string
    {
        $contents = [];
        foreach ($messages as $message) {
            $contents[] = [
                'role' => $message['role'] === 'system' ? 'user' : $message['role'],
                'parts' => [['text' => $message['content']]]
            ];
        }

        try {
            $response = $this->client->post("/models/{$this->model}:generateContent", [
                'query' => ['key' => $this->apiKey],
                'json' => [
                    'contents' => $contents,
                    'generationConfig' => [
                        'maxOutputTokens' => $options['max_tokens'] ?? 150,
                        'temperature' => $options['temperature'] ?? 0.7,
                        'topP' => $options['top_p'] ?? 1.0,
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $this->lastResponse = $data;
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        } catch (\Exception $e) {
            throw AiProviderException::apiError('Gemini', $e->getMessage(), $e->getCode());
        }
    }

    public function embeddings(string $text, array $options = []): array
    {
        try {
            $response = $this->client->post("/models/embedding-001:embedContent", [
                'query' => ['key' => $this->apiKey],
                'json' => [
                    'model' => $options['model'] ?? 'embedding-001',
                    'content' => ['parts' => [['text' => $text]]]
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $this->lastResponse = $data;
            return $data['embedding']['values'] ?? [];
        } catch (\Exception $e) {
            throw AiProviderException::apiError('Gemini', $e->getMessage(), $e->getCode());
        }
    }

    public function getLastRawResponse(): ?array
    {
        return $this->lastResponse;
    }

    public function getAvailableModels(): array
    {
        return [
            'gemini-pro',
            'gemini-pro-vision',
            'embedding-001',
        ];
    }
}