<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookEventMapperInterface;
use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookRepositoryInterface;
use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookServiceInterface;
use Caydeesoft\SaasKit\Webhooks\Repositories\EloquentWebhookRepository;
use Caydeesoft\SaasKit\Webhooks\Services\DefaultWebhookEventMapper;
use Caydeesoft\SaasKit\Webhooks\Services\WebhookService;

final class WebhooksServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WebhookRepositoryInterface::class, EloquentWebhookRepository::class);
        $this->app->bind(WebhookEventMapperInterface::class, DefaultWebhookEventMapper::class);
        $this->app->bind(WebhookServiceInterface::class, WebhookService::class);
    }
}
