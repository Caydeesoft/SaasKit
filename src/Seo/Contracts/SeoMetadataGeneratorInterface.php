<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Seo\Contracts;

use Caydeesoft\SaasKit\Seo\DTOs\SeoMetadataData;

interface SeoMetadataGeneratorInterface
{
    /**
     * @return array<string, mixed>
     */
    public function generate(SeoMetadataData $data): array;
}
