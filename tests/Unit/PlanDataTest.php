<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Caydeesoft\SaasKit\Billing\DTOs\PlanData;

final class PlanDataTest extends TestCase
{
    public function test_it_serializes_plan_data(): void
    {
        $data = new PlanData(
            name: 'Growth',
            key: 'growth',
            interval: 'monthly',
            amount: 4900,
            features: ['projects' => true],
            limits: ['projects' => 25],
        );

        self::assertSame('Growth', $data->toArray()['name']);
        self::assertSame(25, $data->toArray()['limits']['projects']);
    }
}
