<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Users\Contracts\AuthHookManagerInterface;
use Caydeesoft\SaasKit\Users\Contracts\UserProfileRepositoryInterface;
use Caydeesoft\SaasKit\Users\Contracts\UserRepositoryInterface;
use Caydeesoft\SaasKit\Users\Contracts\UserServiceInterface;
use Caydeesoft\SaasKit\Users\Repositories\EloquentUserProfileRepository;
use Caydeesoft\SaasKit\Users\Repositories\EloquentUserRepository;
use Caydeesoft\SaasKit\Users\Services\EventAuthHookManager;
use Caydeesoft\SaasKit\Users\Services\UserService;

final class UsersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(UserProfileRepositoryInterface::class, EloquentUserProfileRepository::class);
        $this->app->bind(AuthHookManagerInterface::class, EventAuthHookManager::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }
}
