<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Api\Contracts;

interface ApiResourceSerializerInterface
{
    /**
     * @param array<string, mixed> $meta
     * @return array<string, mixed>
     */
    public function item(mixed $resource, array $meta = []): array;

    /**
     * @param iterable<int, mixed> $resources
     * @param array<string, mixed> $meta
     * @return array<string, mixed>
     */
    public function collection(iterable $resources, array $meta = []): array;
}
