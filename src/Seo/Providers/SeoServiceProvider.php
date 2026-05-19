<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Seo\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Seo\Contracts\SeoMetadataGeneratorInterface;
use Caydeesoft\SaasKit\Seo\Services\SeoMetadataGenerator;

final class SeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SeoMetadataGeneratorInterface::class, SeoMetadataGenerator::class);
    }
}
