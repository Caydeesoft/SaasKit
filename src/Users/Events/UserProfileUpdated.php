<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Events;

use Caydeesoft\SaasKit\Users\Models\UserProfile;

final readonly class UserProfileUpdated
{
    public function __construct(public UserProfile $profile)
    {
    }
}
