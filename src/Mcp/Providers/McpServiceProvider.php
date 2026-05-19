<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Mcp\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Mcp\Contracts\McpServerInterface;
use Caydeesoft\SaasKit\Mcp\Contracts\McpToolRegistryInterface;
use Caydeesoft\SaasKit\Mcp\Services\McpServer;
use Caydeesoft\SaasKit\Mcp\Services\McpToolRegistry;
use Caydeesoft\SaasKit\Mcp\Tools\HealthTool;
use Caydeesoft\SaasKit\Mcp\Tools\SeoMetadataTool;

final class McpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(McpToolRegistryInterface::class, function ($app): McpToolRegistryInterface {
            $registry = new McpToolRegistry();

            $registry->register($app->make(HealthTool::class));
            $registry->register($app->make(SeoMetadataTool::class));

            return $registry;
        });

        $this->app->singleton(McpServerInterface::class, McpServer::class);
    }
}
