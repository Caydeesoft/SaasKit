<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Contracts;

use Caydeesoft\SaasKit\Users\DTOs\ProfileData;
use Caydeesoft\SaasKit\Users\Models\UserProfile;

interface UserProfileRepositoryInterface
{
    public function findForUser(int|string $userId): ?UserProfile;

    public function updateOrCreate(ProfileData $data): UserProfile;
}
