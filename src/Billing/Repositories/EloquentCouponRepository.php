<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Repositories;

use Caydeesoft\SaasKit\Billing\Contracts\CouponRepositoryInterface;
use Caydeesoft\SaasKit\Billing\DTOs\CouponData;
use Caydeesoft\SaasKit\Billing\Models\Coupon;

final class EloquentCouponRepository implements CouponRepositoryInterface
{
    public function find(int|string $id): ?Coupon
    {
        return Coupon::query()->find($id);
    }

    public function findByCode(string $code): ?Coupon
    {
        return Coupon::query()
            ->where('code', strtoupper($code))
            ->first();
    }

    public function create(CouponData $data): Coupon
    {
        return Coupon::query()->create(array_merge($data->toArray(), [
            'redemptions' => 0,
        ]));
    }
}
