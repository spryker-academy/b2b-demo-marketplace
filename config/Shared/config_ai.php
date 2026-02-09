<?php

declare(strict_types = 1);

use Spryker\Shared\AiFoundation\AiFoundationConstants;

$config[AiFoundationConstants::AI_CONFIGURATIONS] = [
    AiFoundationConstants::AI_CONFIGURATION_DEFAULT => [
        'provider_name' => AiFoundationConstants::PROVIDER_OPENAI,
        'provider_config' => [
            'key' => getenv('OPENAI_API_KEY'),
            'model' => 'gpt-4o',
        ],
        'system_prompt' => 'You are a helpful assistant.',
    ],
    'ollama-config' => [
        'provider_name' => AiFoundationConstants::PROVIDER_OLLAMA,
        'provider_config' => [
            // Use 'http://ollama:11435/api' when Ollama runs inside Docker SDK
            // Use 'http://host.docker.internal:11434/api' for macOS when Ollama runs on host
            'url' => 'http://ollama:11435/api',
            'model' => 'llama3.2',
            'parameters' => [],
            'httpOptions' => [
                'timeout' => 60,
                'connectTimeout' => 5,
            ],
        ],
        // To pull the model after deployment, run:
        // docker/sdk cli exec -c ollama ollama pull llama3.2
    ],
];
