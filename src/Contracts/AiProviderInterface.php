<?php

namespace Syedmahamudul\LaravelAi\Contracts;

interface AiProviderInterface
{
    public function generateText(string $prompt, array $options = []): string;
    public function chat(array $messages, array $options = []): string;
    public function embeddings(string $text, array $options = []): array;
    public function setApiKey(string $apiKey): self;
    public function setModel(string $model): self;
    public function getLastRawResponse(): ?array;
    public function getAvailableModels(): array;
}