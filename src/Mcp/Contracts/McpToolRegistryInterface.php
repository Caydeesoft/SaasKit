<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Mcp\Contracts;

interface McpToolRegistryInterface
{
    public function register(McpToolInterface $tool): void;

    public function find(string $name): ?McpToolInterface;

    /**
     * @return array<int, McpToolInterface>
     */
    public function all(): array;
}
