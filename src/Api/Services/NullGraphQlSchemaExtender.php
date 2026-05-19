<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Api\Services;

use Caydeesoft\SaasKit\Api\Contracts\GraphQlSchemaExtenderInterface;

final class NullGraphQlSchemaExtender implements GraphQlSchemaExtenderInterface
{
    public function types(): array
    {
        return [];
    }

    public function queries(): array
    {
        return [];
    }

    public function mutations(): array
    {
        return [];
    }
}
