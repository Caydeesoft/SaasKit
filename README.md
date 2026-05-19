# SaaS Kit

Production-ready Laravel SaaS infrastructure package for multi-tenant products. It bundles tenancy, users, RBAC, billing, payments, invoices, webhooks, feature gates, audit logs, API hooks, MCP tools, and SEO metadata helpers under the `caydeesoft/saas-kit` package.

## 1. File Structure

```text
composer.json
config/saas-kit.php
database/migrations/2024_01_01_000001_create_saas_kit_tenants_table.php
database/migrations/2024_01_01_000002_create_saas_kit_users_tables.php
database/migrations/2024_01_01_000003_create_saas_kit_rbac_tables.php
database/migrations/2024_01_01_000004_create_saas_kit_billing_tables.php
database/migrations/2024_01_01_000005_create_saas_kit_feature_tables.php
database/migrations/2024_01_01_000006_create_saas_kit_invoices_table.php
database/migrations/2024_01_01_000007_create_saas_kit_webhook_events_table.php
database/migrations/2024_01_01_000008_create_saas_kit_audit_logs_table.php
routes/api.php
src/
  Api/
  Admin/
  Audit/
  Billing/
  Features/
  Invoices/
  Mcp/
  Payments/
  Rbac/
  Seo/
  Tenancy/
  Users/
  Webhooks/
tests/Unit/
```

## 2. Code Per Module

Each module follows the same DDD-lite shape:

- `Contracts`: public interfaces for extension points.
- `DTOs`: immutable input/output objects.
- `Models`: package-owned Eloquent persistence models.
- `Repositories`: Eloquent implementations behind contracts.
- `Services`: business use cases; controllers are intentionally omitted.
- `Events` and `Listeners`: lifecycle hooks and audit integration.
- `Providers`: module-local service container bindings.

Implemented modules:

- `Tenancy`: tenant repository, header resolver, tenant context, pluggable isolation strategies, tenant resolution middleware.
- `Users`: SaaS user model, profile model, CRUD service, authentication hook dispatcher.
- `Rbac`: native roles and permissions with guard names, model-role assignments, permission middleware.
- `Billing`: plans, trials, coupons, discounts, subscriptions, usage records, plan enforcement middleware.
- `Payments`: gateway interface, gateway registry, payment service, Stripe adapter.
- `Invoices`: invoice generation, invoice repository, PDF renderer interface, built-in simple PDF renderer.
- `Webhooks`: raw webhook persistence, provider event normalization, payment/subscription webhook events.
- `Features`: feature flags per plan, limits, feature gate service, feature middleware.
- `Admin`: backend dashboard snapshot service.
- `Audit`: auditable lifecycle events, audit log service, wildcard listener.
- `Api`: JSON resource serializer and optional GraphQL schema extension hook.
- `Mcp`: Streamable HTTP-compatible JSON-RPC endpoint with read-only SaaS Kit tools.
- `Seo`: canonical URLs, meta tags, Open Graph, Twitter card, and JSON-LD metadata generation.

## 3. Service Provider Bindings

Laravel auto-discovers `Caydeesoft\SaasKit\SaasKitServiceProvider`, which registers module providers for tenancy, users, RBAC, billing, payments, invoices, webhooks, feature flags, admin services, audit logs, API hooks, MCP tools, and SEO metadata.

Publish config and migrations:

```bash
php artisan vendor:publish --tag=saas-kit-config
php artisan vendor:publish --tag=saas-kit-migrations
php artisan migrate
```

Middleware aliases:

```php
Route::middleware(['saas.tenant', 'saas.subscription', 'saas.feature:exports'])->group(function () {
    // Application routes.
});
```

API endpoints:

```text
GET  /api/saas-kit/health
POST /api/saas-kit/mcp
GET  /api/saas-kit/mcp
```

The MCP endpoint follows the 2025-11-25 JSON-RPC message shape for initialization, `tools/list`, and `tools/call`. The GET method returns `405 Method Not Allowed` when server-sent event streaming is not enabled.

## 4. Example Usage

```php
use Caydeesoft\SaasKit\Billing\Contracts\BillingServiceInterface;
use Caydeesoft\SaasKit\Billing\DTOs\PlanData;

$billing = app(BillingServiceInterface::class);

$plan = $billing->createPlan(new PlanData(
    name: 'Growth',
    key: 'growth',
    interval: 'monthly',
    amount: 4900,
    currency: 'usd',
    features: ['projects' => 25, 'seats' => 10],
));
```

```php
use Caydeesoft\SaasKit\Payments\Contracts\PaymentServiceInterface;
use Caydeesoft\SaasKit\Payments\DTOs\CheckoutSessionData;

$payments = app(PaymentServiceInterface::class);

$session = $payments->createCheckoutSession(new CheckoutSessionData(
    customerEmail: 'founder@example.com',
    successUrl: 'https://app.example.com/billing/success',
    cancelUrl: 'https://app.example.com/billing/cancel',
    lineItems: [['price' => 'price_123', 'quantity' => 1]],
));
```

```php
use Caydeesoft\SaasKit\Features\Contracts\FeatureGateInterface;
use Caydeesoft\SaasKit\Features\DTOs\FeatureData;

$features = app(FeatureGateInterface::class);

$features->createFeature(new FeatureData('Exports', 'exports'));
$features->enableForPlan($plan->id, 'exports', limit: 100);
```

```php
use Caydeesoft\SaasKit\Invoices\Contracts\InvoiceServiceInterface;
use Caydeesoft\SaasKit\Invoices\DTOs\InvoiceLineData;

$invoice = app(InvoiceServiceInterface::class)->generateForSubscription($subscription, [
    new InvoiceLineData('Growth plan', 1, 4900),
]);

$pdfBytes = app(InvoiceServiceInterface::class)->exportPdf($invoice);
```

## 5. MCP Usage

```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "method": "initialize",
  "params": {
    "protocolVersion": "2025-11-25",
    "capabilities": {},
    "clientInfo": {
      "name": "example-client",
      "version": "1.0.0"
    }
  }
}
```

Available tools:

- `saas_kit.health`: returns package health status.
- `saas_kit.seo.metadata`: generates canonical, meta, Open Graph, Twitter, and JSON-LD metadata for a SaaS page.

```json
{
  "jsonrpc": "2.0",
  "id": 2,
  "method": "tools/call",
  "params": {
    "name": "saas_kit.seo.metadata",
    "arguments": {
      "title": "Billing Automation for SaaS Teams",
      "description": "Launch subscription billing, invoices, and feature gates faster in Laravel.",
      "url": "https://example.com/billing",
      "siteName": "Example SaaS",
      "keywords": ["laravel saas", "subscription billing"]
    }
  }
}
```

## 6. SEO Usage

```php
use Caydeesoft\SaasKit\Seo\Contracts\SeoMetadataGeneratorInterface;
use Caydeesoft\SaasKit\Seo\DTOs\SeoMetadataData;

$metadata = app(SeoMetadataGeneratorInterface::class)->generate(new SeoMetadataData(
    title: 'Subscription Billing for Laravel SaaS',
    description: 'Create plans, trials, invoices, payment workflows, and feature limits with SaaS Kit.',
    url: 'https://example.com/billing',
    siteName: 'Example SaaS',
    keywords: ['laravel saas', 'billing', 'subscriptions'],
));

// Use the returned canonical, meta, open_graph, twitter, and json_ld arrays in your Blade or frontend head renderer.
```
