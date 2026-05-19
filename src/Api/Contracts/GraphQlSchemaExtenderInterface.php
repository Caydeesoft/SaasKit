<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Api\Contracts;

interface GraphQlSchemaExtenderInterface
{
    /**
     * @return array<string, mixed>
     */
    public function types(): array;

    /**
     * @return array<string, mixed>
     */
    public function queries(): array;

    /**
     * @return array<string, mixed>
     */
    public function mutations(): array;
}
