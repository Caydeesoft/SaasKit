<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\Events;

use Caydeesoft\SaasKit\Webhooks\DTOs\WebhookEventData;

final readonly class WebhookReceived
{
    public function __construct(public WebhookEventData $data)
    {
    }
}
