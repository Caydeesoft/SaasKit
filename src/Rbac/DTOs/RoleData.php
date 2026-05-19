<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\DTOs;

final readonly class RoleData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $name,
        public string $key,
        public string $guardName = 'web',
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
            'guard_name' => $this->guardName,
            'metadata' => $this->metadata,
        ];
    }
}
