<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Caydeesoft\SaasKit\Api\Http\Controllers\HealthController;
use Caydeesoft\SaasKit\Mcp\Http\Controllers\McpController;

Route::get('/health', HealthController::class);

if ((bool) config('saas-kit.mcp.enabled', true)) {
    Route::match(['GET', 'POST', 'DELETE'], '/mcp', McpController::class);
}
