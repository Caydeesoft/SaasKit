<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Contracts;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Users\DTOs\UserData;
use Caydeesoft\SaasKit\Users\Models\SaasUser;

interface UserRepositoryInterface
{
    public function find(int|string $id): ?SaasUser;

    public function findByEmail(string $email): ?SaasUser;

    /**
     * @return Collection<int, SaasUser>
     */
    public function forTenant(int|string $tenantId): Collection;

    public function create(UserData $data): SaasUser;

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(SaasUser $user, array $attributes): SaasUser;

    public function delete(SaasUser $user): bool;
}
