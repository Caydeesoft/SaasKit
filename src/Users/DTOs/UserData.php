<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\DTOs;

final readonly class UserData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public int|string|null $tenantId = null,
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
            email: (string) $data['email'],
            password: isset($data['password']) ? (string) $data['password'] : null,
            tenantId: $data['tenant_id'] ?? null,
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
            'tenant_id' => $this->tenantId,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'status' => $this->status,
            'metadata' => $this->metadata,
        ];
    }
}
