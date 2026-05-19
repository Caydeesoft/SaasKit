<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Mcp\Contracts;

interface McpServerInterface
{
    /**
     * @param array<string, mixed> $message
     * @return array<string, mixed>|null
     */
    public function handle(array $message): ?array;
}
