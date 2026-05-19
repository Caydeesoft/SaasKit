<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Mcp\Services;

use Caydeesoft\SaasKit\Mcp\Contracts\McpToolInterface;
use Caydeesoft\SaasKit\Mcp\Contracts\McpToolRegistryInterface;

final class McpToolRegistry implements McpToolRegistryInterface
{
    /**
     * @var array<string, McpToolInterface>
     */
    private array $tools = [];

    public function register(McpToolInterface $tool): void
    {
        $this->tools[$tool->name()] = $tool;
        ksort($this->tools);
    }

    public function find(string $name): ?McpToolInterface
    {
        return $this->tools[$name] ?? null;
    }

    public function all(): array
    {
        return array_values($this->tools);
    }
}
