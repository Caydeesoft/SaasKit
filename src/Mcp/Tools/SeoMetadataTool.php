<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Mcp\Tools;

use Caydeesoft\SaasKit\Mcp\Contracts\McpToolInterface;
use Caydeesoft\SaasKit\Seo\Contracts\SeoMetadataGeneratorInterface;
use Caydeesoft\SaasKit\Seo\DTOs\SeoMetadataData;

final class SeoMetadataTool implements McpToolInterface
{
    public function __construct(
        private readonly SeoMetadataGeneratorInterface $metadata,
    ) {
    }

    public function name(): string
    {
        return 'saas_kit.seo.metadata';
    }

    public function title(): string
    {
        return 'SEO Metadata';
    }

    public function description(): string
    {
        return 'Generate canonical, Open Graph, Twitter, meta, and JSON-LD metadata for a SaaS page.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'title' => [
                    'type' => 'string',
                    'description' => 'Human-readable page title.',
                ],
                'description' => [
                    'type' => 'string',
                    'description' => 'Search result description for the page.',
                ],
                'url' => [
                    'type' => 'string',
                    'format' => 'uri',
                    'description' => 'Canonical absolute URL, or root-relative URL when app.url is configured.',
                ],
                'image' => [
                    'type' => 'string',
                    'format' => 'uri',
                    'description' => 'Optional social preview image URL.',
                ],
                'siteName' => [
                    'type' => 'string',
                    'description' => 'Optional site or product name.',
                ],
                'type' => [
                    'type' => 'string',
                    'description' => 'Open Graph content type.',
                ],
                'keywords' => [
                    'type' => 'array',
                    'description' => 'Optional keyword list.',
                ],
                'robots' => [
                    'type' => 'string',
                    'description' => 'Robots directive, such as index,follow.',
                ],
            ],
            'required' => ['title', 'description', 'url'],
        ];
    }

    public function outputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'description' => ['type' => 'string'],
                'canonical' => ['type' => 'string'],
                'robots' => ['type' => 'string'],
                'meta' => ['type' => 'array'],
                'open_graph' => ['type' => 'array'],
                'twitter' => ['type' => 'array'],
                'json_ld' => ['type' => 'object'],
            ],
            'required' => ['title', 'description', 'canonical', 'robots', 'meta', 'open_graph', 'twitter', 'json_ld'],
        ];
    }

    public function annotations(): array
    {
        return [
            'readOnlyHint' => true,
            'destructiveHint' => false,
            'idempotentHint' => true,
            'openWorldHint' => false,
        ];
    }

    public function call(array $arguments): array
    {
        $metadata = $this->metadata->generate(SeoMetadataData::fromArray($arguments));

        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                ],
            ],
            'structuredContent' => $metadata,
            'isError' => false,
        ];
    }
}
