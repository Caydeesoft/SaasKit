<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tests\Unit;

use Caydeesoft\SaasKit\Tests\TestCase;

final class McpEndpointTest extends TestCase
{
    public function test_it_initializes_the_mcp_server(): void
    {
        $this->postJson('/api/saas-kit/mcp', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'initialize',
            'params' => [
                'protocolVersion' => '2025-11-25',
                'capabilities' => [],
                'clientInfo' => [
                    'name' => 'phpunit',
                    'version' => '1.0.0',
                ],
            ],
        ])
            ->assertOk()
            ->assertJsonPath('result.protocolVersion', '2025-11-25')
            ->assertJsonPath('result.capabilities.tools.listChanged', false)
            ->assertJsonPath('result.serverInfo.name', 'caydeesoft/saas-kit');
    }

    public function test_it_lists_mcp_tools(): void
    {
        $this->postJson('/api/saas-kit/mcp', [
            'jsonrpc' => '2.0',
            'id' => 2,
            'method' => 'tools/list',
        ])
            ->assertOk()
            ->assertJsonPath('result.tools.0.name', 'saas_kit.health')
            ->assertJsonPath('result.tools.0.annotations.readOnlyHint', true)
            ->assertJsonPath('result.tools.1.name', 'saas_kit.seo.metadata');
    }

    public function test_it_calls_the_seo_metadata_tool(): void
    {
        $this->postJson('/api/saas-kit/mcp', [
            'jsonrpc' => '2.0',
            'id' => 3,
            'method' => 'tools/call',
            'params' => [
                'name' => 'saas_kit.seo.metadata',
                'arguments' => [
                    'title' => 'Laravel SaaS Billing',
                    'description' => 'Launch billing, invoices, and subscriptions for Laravel SaaS products.',
                    'url' => 'https://example.com/billing',
                    'siteName' => 'Example SaaS',
                    'keywords' => ['laravel', 'saas'],
                ],
            ],
        ])
            ->assertOk()
            ->assertJsonPath('result.isError', false)
            ->assertJsonPath('result.structuredContent.canonical', 'https://example.com/billing')
            ->assertJsonPath('result.structuredContent.json_ld.@context', 'https://schema.org');
    }

    public function test_tool_argument_errors_are_returned_as_tool_results(): void
    {
        $this->postJson('/api/saas-kit/mcp', [
            'jsonrpc' => '2.0',
            'id' => 4,
            'method' => 'tools/call',
            'params' => [
                'name' => 'saas_kit.seo.metadata',
                'arguments' => [
                    'description' => 'Missing a title.',
                    'url' => 'https://example.com',
                ],
            ],
        ])
            ->assertOk()
            ->assertJsonPath('result.isError', true)
            ->assertJsonPath('result.content.0.text', 'Missing required argument: title');
    }

    public function test_it_rejects_invalid_mcp_origins(): void
    {
        $this->withHeader('Origin', 'https://attacker.example')
            ->postJson('/api/saas-kit/mcp', [
                'jsonrpc' => '2.0',
                'id' => 5,
                'method' => 'ping',
            ])
            ->assertForbidden();
    }

    public function test_mcp_get_returns_method_not_allowed_without_sse(): void
    {
        $this->get('/api/saas-kit/mcp')->assertMethodNotAllowed();
    }
}
