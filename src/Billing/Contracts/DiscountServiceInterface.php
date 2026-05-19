<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Contracts;

use Caydeesoft\SaasKit\Billing\Models\Coupon;

interface DiscountServiceInterface
{
    public function isValid(Coupon $coupon): bool;

    public function discountFor(Coupon $coupon, int $amount): int;
}
