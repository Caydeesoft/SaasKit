<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Caydeesoft\SaasKit\Billing\Models\Coupon;
use Caydeesoft\SaasKit\Billing\Services\DiscountService;

final class DiscountServiceTest extends TestCase
{
    public function test_it_calculates_percent_discounts(): void
    {
        $coupon = new Coupon([
            'type' => 'percent',
            'value' => 20,
            'redemptions' => 0,
        ]);

        self::assertSame(2000, (new DiscountService())->discountFor($coupon, 10000));
    }

    public function test_it_caps_fixed_discounts_at_amount(): void
    {
        $coupon = new Coupon([
            'type' => 'fixed',
            'value' => 12000,
            'redemptions' => 0,
        ]);

        self::assertSame(10000, (new DiscountService())->discountFor($coupon, 10000));
    }
}
