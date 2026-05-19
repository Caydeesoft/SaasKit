<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Api\Contracts\ApiResourceSerializerInterface;
use Caydeesoft\SaasKit\Api\Contracts\GraphQlSchemaExtenderInterface;
use Caydeesoft\SaasKit\Api\Services\JsonApiResourceSerializer;
use Caydeesoft\SaasKit\Api\Services\NullGraphQlSchemaExtender;

final class ApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ApiResourceSerializerInterface::class, JsonApiResourceSerializer::class);
        $this->app->bind(GraphQlSchemaExtenderInterface::class, NullGraphQlSchemaExtender::class);
    }
}
