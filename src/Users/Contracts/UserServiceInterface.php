<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Contracts;

use Caydeesoft\SaasKit\Tenancy\Models\Tenant;
use Caydeesoft\SaasKit\Users\DTOs\ProfileData;
use Caydeesoft\SaasKit\Users\DTOs\UserData;
use Caydeesoft\SaasKit\Users\Models\SaasUser;
use Caydeesoft\SaasKit\Users\Models\UserProfile;

interface UserServiceInterface
{
    public function createUser(UserData $data): SaasUser;

    /**
     * @param array<string, mixed> $attributes
     */
    public function updateUser(SaasUser $user, array $attributes): SaasUser;

    public function updateProfile(ProfileData $data): UserProfile;

    public function deleteUser(SaasUser $user): bool;

    public function handleAuthenticated(mixed $user, ?Tenant $tenant = null): void;
}
