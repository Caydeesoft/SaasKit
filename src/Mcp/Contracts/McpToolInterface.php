<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Mcp\Contracts;

interface McpToolInterface
{
    public function name(): string;

    public function title(): string;

    public function description(): string;

    /**
     * @return array<string, mixed>
     */
    public function inputSchema(): array;

    /**
     * @return array<string, mixed>
     */
    public function outputSchema(): array;

    /**
     * @return array<string, mixed>
     */
    public function annotations(): array;

    /**
     * @param array<string, mixed> $arguments
     * @return array<string, mixed>
     */
    public function call(array $arguments): array;
}
