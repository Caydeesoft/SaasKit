<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\Contracts;

use Caydeesoft\SaasKit\Webhooks\DTOs\WebhookEventData;

interface WebhookEventMapperInterface
{
    public function map(WebhookEventData $data): object;
}
