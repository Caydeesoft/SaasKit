<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Repositories;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Users\Contracts\UserRepositoryInterface;
use Caydeesoft\SaasKit\Users\DTOs\UserData;
use Caydeesoft\SaasKit\Users\Models\SaasUser;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function find(int|string $id): ?SaasUser
    {
        return SaasUser::query()->find($id);
    }

    public function findByEmail(string $email): ?SaasUser
    {
        return SaasUser::query()
            ->where('email', $email)
            ->first();
    }

    public function forTenant(int|string $tenantId): Collection
    {
        return SaasUser::query()
            ->where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();
    }

    public function create(UserData $data): SaasUser
    {
        return SaasUser::query()->create($data->toArray());
    }

    public function update(SaasUser $user, array $attributes): SaasUser
    {
        $user->fill($attributes);
        $user->save();

        return $user->refresh();
    }

    public function delete(SaasUser $user): bool
    {
        return (bool) $user->delete();
    }
}
