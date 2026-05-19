<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Admin\Contracts;

use Caydeesoft\SaasKit\Admin\DTOs\DashboardSnapshotData;

interface AdminDashboardServiceInterface
{
    public function snapshot(): DashboardSnapshotData;
}
