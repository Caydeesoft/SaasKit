<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\Contracts;

use Caydeesoft\SaasKit\Webhooks\DTOs\WebhookEventData;
use Caydeesoft\SaasKit\Webhooks\Models\WebhookEvent;

interface WebhookServiceInterface
{
    public function receive(WebhookEventData $data): WebhookEvent;

    public function markProcessed(WebhookEvent $event): WebhookEvent;

    public function markFailed(WebhookEvent $event, string $reason): WebhookEvent;
}
