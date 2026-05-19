<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Repositories;

use Caydeesoft\SaasKit\Users\Contracts\UserProfileRepositoryInterface;
use Caydeesoft\SaasKit\Users\DTOs\ProfileData;
use Caydeesoft\SaasKit\Users\Models\UserProfile;

final class EloquentUserProfileRepository implements UserProfileRepositoryInterface
{
    public function findForUser(int|string $userId): ?UserProfile
    {
        return UserProfile::query()
            ->where('user_id', $userId)
            ->first();
    }

    public function updateOrCreate(ProfileData $data): UserProfile
    {
        return UserProfile::query()->updateOrCreate(
            ['user_id' => $data->userId],
            $data->toArray(),
        );
    }
}
