<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Features\DTOs;

final readonly class FeatureData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $name,
        public string $key,
        public ?string $description = null,
        public array $metadata = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'key' => $this->key,
            'description' => $this->description,
            'metadata' => $this->metadata,
        ];
    }
}
