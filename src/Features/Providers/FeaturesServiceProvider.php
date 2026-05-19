<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Features\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Features\Contracts\FeatureGateInterface;
use Caydeesoft\SaasKit\Features\Contracts\FeatureRepositoryInterface;
use Caydeesoft\SaasKit\Features\Repositories\EloquentFeatureRepository;
use Caydeesoft\SaasKit\Features\Services\FeatureGate;

final class FeaturesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FeatureRepositoryInterface::class, EloquentFeatureRepository::class);
        $this->app->bind(FeatureGateInterface::class, FeatureGate::class);
    }
}
