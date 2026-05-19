<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Services;

use Caydeesoft\SaasKit\Billing\Contracts\DiscountServiceInterface;
use Caydeesoft\SaasKit\Billing\Models\Coupon;

final class DiscountService implements DiscountServiceInterface
{
    public function isValid(Coupon $coupon): bool
    {
        if ($coupon->valid_from !== null && $coupon->valid_from->isFuture()) {
            return false;
        }

        if ($coupon->valid_until !== null && $coupon->valid_until->isPast()) {
            return false;
        }

        if ($coupon->max_redemptions !== null && $coupon->redemptions >= $coupon->max_redemptions) {
            return false;
        }

        return true;
    }

    public function discountFor(Coupon $coupon, int $amount): int
    {
        if (! $this->isValid($coupon)) {
            return 0;
        }

        if ($coupon->type === 'percent') {
            return min($amount, (int) floor($amount * ((int) $coupon->value / 100)));
        }

        if ($coupon->type === 'fixed') {
            return min($amount, (int) $coupon->value);
        }

        return 0;
    }
}
