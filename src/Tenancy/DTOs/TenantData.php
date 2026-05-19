<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\DTOs;

final readonly class TenantData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public string $status = 'active',
        public array $metadata = [],
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            slug: isset($data['slug']) ? (string) $data['slug'] : null,
            status: (string) ($data['status'] ?? 'active'),
            metadata: (array) ($data['metadata'] ?? []),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => $this->status,
            'metadata' => $this->metadata,
        ];
    }
}
