<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Mcp\Tools;

use Caydeesoft\SaasKit\Mcp\Contracts\McpToolInterface;

final class HealthTool implements McpToolInterface
{
    public function name(): string
    {
        return 'saas_kit.health';
    }

    public function title(): string
    {
        return 'SaaS Kit Health';
    }

    public function description(): string
    {
        return 'Return the package health status for MCP clients.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [],
            'required' => [],
        ];
    }

    public function outputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'package' => ['type' => 'string'],
                'status' => ['type' => 'string'],
            ],
            'required' => ['package', 'status'],
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
        $data = [
            'package' => (string) config('saas-kit.package.name', 'caydeesoft/saas-kit'),
            'status' => 'ok',
        ];

        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => json_encode($data, JSON_THROW_ON_ERROR),
                ],
            ],
            'structuredContent' => $data,
            'isError' => false,
        ];
    }
}
