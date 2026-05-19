<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\DTOs;

final readonly class ProfileData
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        public int|string $userId,
        public ?string $displayName = null,
        public ?string $timezone = null,
        public ?string $locale = null,
        public array $attributes = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'display_name' => $this->displayName,
            'timezone' => $this->timezone,
            'locale' => $this->locale,
            'attributes' => $this->attributes,
        ];
    }
}
