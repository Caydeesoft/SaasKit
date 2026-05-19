<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Hash;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;
use Caydeesoft\SaasKit\Users\Contracts\AuthHookManagerInterface;
use Caydeesoft\SaasKit\Users\Contracts\UserProfileRepositoryInterface;
use Caydeesoft\SaasKit\Users\Contracts\UserRepositoryInterface;
use Caydeesoft\SaasKit\Users\Contracts\UserServiceInterface;
use Caydeesoft\SaasKit\Users\DTOs\ProfileData;
use Caydeesoft\SaasKit\Users\DTOs\UserData;
use Caydeesoft\SaasKit\Users\Events\UserCreated;
use Caydeesoft\SaasKit\Users\Events\UserProfileUpdated;
use Caydeesoft\SaasKit\Users\Models\SaasUser;
use Caydeesoft\SaasKit\Users\Models\UserProfile;

final class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly UserProfileRepositoryInterface $profiles,
        private readonly AuthHookManagerInterface $authHooks,
        private readonly Dispatcher $events,
    ) {
    }

    public function createUser(UserData $data): SaasUser
    {
        $payload = $data->toArray();

        if (is_string($payload['password']) && $payload['password'] !== '') {
            $payload['password'] = Hash::make($payload['password']);
        }

        $user = $this->users->create(UserData::fromArray($payload));
        $this->events->dispatch(new UserCreated($user));

        return $user;
    }

    public function updateUser(SaasUser $user, array $attributes): SaasUser
    {
        if (isset($attributes['password']) && is_string($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        return $this->users->update($user, $attributes);
    }

    public function updateProfile(ProfileData $data): UserProfile
    {
        $profile = $this->profiles->updateOrCreate($data);
        $this->events->dispatch(new UserProfileUpdated($profile));

        return $profile;
    }

    public function deleteUser(SaasUser $user): bool
    {
        return $this->users->delete($user);
    }

    public function handleAuthenticated(mixed $user, ?Tenant $tenant = null): void
    {
        $this->authHooks->dispatchAuthenticated($user, $tenant);
    }
}
