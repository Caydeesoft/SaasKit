<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Api\Services;

use Illuminate\Contracts\Support\Arrayable;
use Caydeesoft\SaasKit\Api\Contracts\ApiResourceSerializerInterface;

final class JsonApiResourceSerializer implements ApiResourceSerializerInterface
{
    public function item(mixed $resource, array $meta = []): array
    {
        return [
            'data' => $this->normalize($resource),
            'meta' => $meta,
        ];
    }

    public function collection(iterable $resources, array $meta = []): array
    {
        $data = [];

        foreach ($resources as $resource) {
            $data[] = $this->normalize($resource);
        }

        return [
            'data' => $data,
            'meta' => $meta,
        ];
    }

    private function normalize(mixed $resource): mixed
    {
        if ($resource instanceof Arrayable) {
            return $resource->toArray();
        }

        if (is_object($resource) && method_exists($resource, 'toArray')) {
            return $resource->toArray();
        }

        return $resource;
    }
}
