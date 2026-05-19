<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class HealthController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'package' => (string) config('saas-kit.package.name', 'caydeesoft/saas-kit'),
            'status' => 'ok',
        ]);
    }
}
