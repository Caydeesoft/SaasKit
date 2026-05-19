<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Contracts;

use Caydeesoft\SaasKit\Billing\DTOs\CouponData;
use Caydeesoft\SaasKit\Billing\Models\Coupon;

interface CouponRepositoryInterface
{
    public function find(int|string $id): ?Coupon;

    public function findByCode(string $code): ?Coupon;

    public function create(CouponData $data): Coupon;
}
