<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Audit\Contracts;

use Caydeesoft\SaasKit\Audit\DTOs\AuditLogData;
use Caydeesoft\SaasKit\Audit\Events\AuditableEventInterface;
use Caydeesoft\SaasKit\Audit\Models\AuditLog;

interface AuditServiceInterface
{
    public function record(AuditLogData $data): AuditLog;

    public function recordEvent(AuditableEventInterface $event): AuditLog;
}
