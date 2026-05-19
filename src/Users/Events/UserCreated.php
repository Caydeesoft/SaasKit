<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Events;

use Caydeesoft\SaasKit\Audit\Events\AuditableEventInterface;
use Caydeesoft\SaasKit\Users\Models\SaasUser;

final readonly class UserCreated implements AuditableEventInterface
{
    public function __construct(public SaasUser $user)
    {
    }

    public function auditPayload(): array
    {
        return [
            'event' => 'user.created',
            'subject_type' => $this->user::class,
            'subject_id' => (string) $this->user->getKey(),
            'metadata' => ['email' => $this->user->email],
        ];
    }
}
