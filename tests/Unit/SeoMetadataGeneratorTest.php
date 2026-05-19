<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tests\Unit;

use Caydeesoft\SaasKit\Seo\DTOs\SeoMetadataData;
use Caydeesoft\SaasKit\Seo\Services\SeoMetadataGenerator;
use Caydeesoft\SaasKit\Tests\TestCase;

final class SeoMetadataGeneratorTest extends TestCase
{
    public function test_it_generates_search_and_social_metadata(): void
    {
        $metadata = (new SeoMetadataGenerator())->generate(new SeoMetadataData(
            title: 'Laravel SaaS Billing',
            description: 'Launch billing, invoices, and subscription workflows for Laravel SaaS products.',
            url: 'https://example.com/billing',
            image: 'https://example.com/preview.png',
            siteName: 'Example SaaS',
            keywords: ['laravel', 'saas', 'billing'],
        ));

        self::assertSame('https://example.com/billing', $metadata['canonical']);
        self::assertSame('https://schema.org', $metadata['json_ld']['@context']);
        self::assertContains(['name' => 'keywords', 'content' => 'laravel, saas, billing'], $metadata['meta']);
        self::assertContains(['property' => 'og:image', 'content' => 'https://example.com/preview.png'], $metadata['open_graph']);
    }
}
