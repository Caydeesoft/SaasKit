<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Seo\DTOs;

final readonly class SeoMetadataData
{
    /**
     * @param array<int, string> $keywords
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $url,
        public ?string $image = null,
        public ?string $siteName = null,
        public string $type = 'website',
        public array $keywords = [],
        public ?string $robots = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: (string) ($data['title'] ?? ''),
            description: (string) ($data['description'] ?? ''),
            url: (string) ($data['url'] ?? ''),
            image: isset($data['image']) ? (string) $data['image'] : null,
            siteName: isset($data['siteName']) ? (string) $data['siteName'] : null,
            type: (string) ($data['type'] ?? 'website'),
            keywords: array_values(array_filter(
                array_map(static fn (mixed $keyword): string => trim((string) $keyword), (array) ($data['keywords'] ?? [])),
                static fn (string $keyword): bool => $keyword !== '',
            )),
            robots: isset($data['robots']) ? (string) $data['robots'] : null,
        );
    }
}
