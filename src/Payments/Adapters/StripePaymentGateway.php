<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Payments\Adapters;

use Caydeesoft\SaasKit\Payments\Contracts\PaymentGatewayInterface;
use Caydeesoft\SaasKit\Payments\DTOs\CheckoutSessionData;
use Caydeesoft\SaasKit\Payments\DTOs\PaymentResultData;
use Caydeesoft\SaasKit\Payments\Exceptions\PaymentGatewayException;
use Caydeesoft\SaasKit\Webhooks\DTOs\WebhookEventData;

final class StripePaymentGateway implements PaymentGatewayInterface
{
    public function name(): string
    {
        return 'stripe';
    }

    public function createCheckoutSession(CheckoutSessionData $data): PaymentResultData
    {
        if (! class_exists(\Stripe\StripeClient::class)) {
            throw new PaymentGatewayException('Install stripe/stripe-php to use the Stripe gateway.');
        }

        $secret = config('saas-kit.payments.stripe.secret');

        if (! is_string($secret) || $secret === '') {
            throw new PaymentGatewayException('Stripe secret is not configured.');
        }

        $client = new \Stripe\StripeClient($secret);
        $session = $client->checkout->sessions->create([
            'mode' => $data->mode,
            'customer_email' => $data->customerEmail,
            'success_url' => $data->successUrl,
            'cancel_url' => $data->cancelUrl,
            'line_items' => $data->lineItems,
            'metadata' => $data->metadata,
        ]);

        $payload = method_exists($session, 'toArray') ? $session->toArray() : (array) $session;

        return new PaymentResultData(
            success: true,
            provider: $this->name(),
            providerId: $session->id ?? null,
            status: $session->status ?? 'created',
            payload: $payload,
            redirectUrl: $session->url ?? null,
        );
    }

    public function cancelSubscription(string $providerSubscriptionId): PaymentResultData
    {
        if (! class_exists(\Stripe\StripeClient::class)) {
            throw new PaymentGatewayException('Install stripe/stripe-php to use the Stripe gateway.');
        }

        $secret = config('saas-kit.payments.stripe.secret');

        if (! is_string($secret) || $secret === '') {
            throw new PaymentGatewayException('Stripe secret is not configured.');
        }

        $client = new \Stripe\StripeClient($secret);
        $subscription = $client->subscriptions->cancel($providerSubscriptionId);
        $payload = method_exists($subscription, 'toArray') ? $subscription->toArray() : (array) $subscription;

        return new PaymentResultData(
            success: true,
            provider: $this->name(),
            providerId: $providerSubscriptionId,
            status: $subscription->status ?? 'cancelled',
            payload: $payload,
        );
    }

    public function parseWebhook(string $payload, array $headers = []): WebhookEventData
    {
        $signature = $headers['Stripe-Signature'] ?? $headers['stripe-signature'] ?? null;
        $secret = config('saas-kit.payments.stripe.webhook_secret');

        if (is_string($secret) && $secret !== '' && is_string($signature)) {
            if (! class_exists(\Stripe\Webhook::class)) {
                throw new PaymentGatewayException('Install stripe/stripe-php to validate Stripe webhooks.');
            }

            $event = \Stripe\Webhook::constructEvent($payload, $signature, $secret);
            $decoded = method_exists($event, 'toArray') ? $event->toArray() : (array) $event;
        } else {
            $decoded = json_decode($payload, true, flags: JSON_THROW_ON_ERROR);
        }

        if (! is_array($decoded)) {
            throw new PaymentGatewayException('Invalid Stripe webhook payload.');
        }

        return new WebhookEventData(
            provider: $this->name(),
            eventType: (string) ($decoded['type'] ?? 'unknown'),
            providerEventId: (string) ($decoded['id'] ?? hash('sha256', $payload)),
            payload: $decoded,
            headers: $headers,
        );
    }
}
