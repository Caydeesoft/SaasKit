<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Mcp\Services;

use Throwable;
use Caydeesoft\SaasKit\Mcp\Contracts\McpServerInterface;
use Caydeesoft\SaasKit\Mcp\Contracts\McpToolInterface;
use Caydeesoft\SaasKit\Mcp\Contracts\McpToolRegistryInterface;

final class McpServer implements McpServerInterface
{
    public function __construct(
        private readonly McpToolRegistryInterface $tools,
    ) {
    }

    public function handle(array $message): ?array
    {
        $id = $message['id'] ?? null;
        $method = $message['method'] ?? null;

        if (($message['jsonrpc'] ?? null) !== '2.0' || ! is_string($method)) {
            return $this->error($id, -32600, 'Invalid Request');
        }

        if (! array_key_exists('id', $message)) {
            return null;
        }

        return match ($method) {
            'initialize' => $this->success($id, $this->initializeResult()),
            'ping' => $this->success($id, []),
            'tools/list' => $this->success($id, ['tools' => $this->toolDefinitions()]),
            'tools/call' => $this->callTool($id, (array) ($message['params'] ?? [])),
            default => $this->error($id, -32601, "Method not found: {$method}"),
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function initializeResult(): array
    {
        return [
            'protocolVersion' => (string) config('saas-kit.mcp.protocol_version', '2025-06-18'),
            'capabilities' => [
                'tools' => [
                    'listChanged' => false,
                ],
            ],
            'serverInfo' => [
                'name' => (string) config('saas-kit.mcp.server_name', 'caydeesoft/saas-kit'),
                'title' => (string) config('saas-kit.mcp.server_title', 'SaaS Kit MCP Server'),
                'version' => (string) config('saas-kit.package.version', '0.1.0'),
                'description' => (string) config('saas-kit.mcp.server_description', ''),
            ],
            'instructions' => (string) config('saas-kit.mcp.instructions', ''),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function toolDefinitions(): array
    {
        return array_map(
            static fn (McpToolInterface $tool): array => [
                'name' => $tool->name(),
                'title' => $tool->title(),
                'description' => $tool->description(),
                'inputSchema' => $tool->inputSchema(),
                'outputSchema' => $tool->outputSchema(),
                'annotations' => $tool->annotations(),
            ],
            $this->tools->all(),
        );
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    private function callTool(mixed $id, array $params): array
    {
        $name = $params['name'] ?? null;
        $arguments = $params['arguments'] ?? [];

        if (! is_string($name) || ! is_array($arguments)) {
            return $this->error($id, -32602, 'tools/call requires a tool name and object arguments.');
        }

        $tool = $this->tools->find($name);

        if ($tool === null) {
            return $this->error($id, -32602, "Unknown tool: {$name}");
        }

        $validationError = $this->validateArguments($tool, $arguments);

        if ($validationError !== null) {
            return $this->toolError($id, $validationError);
        }

        try {
            return $this->success($id, $tool->call($arguments));
        } catch (Throwable $exception) {
            return $this->success($id, [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $exception->getMessage(),
                    ],
                ],
                'isError' => true,
            ]);
        }
    }

    /**
     * @param array<string, mixed> $arguments
     */
    private function validateArguments(McpToolInterface $tool, array $arguments): ?string
    {
        $schema = $tool->inputSchema();
        $required = (array) ($schema['required'] ?? []);

        foreach ($required as $field) {
            if (is_string($field) && ! array_key_exists($field, $arguments)) {
                return "Missing required argument: {$field}";
            }
        }

        $properties = (array) ($schema['properties'] ?? []);

        foreach ($arguments as $name => $value) {
            $property = $properties[$name] ?? null;

            if (! is_array($property) || ! isset($property['type'])) {
                continue;
            }

            $expectedType = (string) $property['type'];

            if (! $this->matchesType($value, $expectedType)) {
                return "Argument {$name} must be of type {$expectedType}.";
            }
        }

        return null;
    }

    private function matchesType(mixed $value, string $expectedType): bool
    {
        return match ($expectedType) {
            'array' => is_array($value),
            'boolean' => is_bool($value),
            'integer' => is_int($value),
            'number' => is_int($value) || is_float($value),
            'object' => is_array($value),
            'string' => is_string($value),
            default => true,
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function toolError(mixed $id, string $message): array
    {
        return $this->success($id, [
            'content' => [
                [
                    'type' => 'text',
                    'text' => $message,
                ],
            ],
            'isError' => true,
        ]);
    }

    /**
     * @param array<string, mixed> $result
     * @return array<string, mixed>
     */
    private function success(mixed $id, array $result): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => $result,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function error(mixed $id, int $code, string $message): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ];
    }
}
