<?php

namespace App\Models;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;

    public function getNotCompletedOrderSlotsIdsForShop($shopId)
    {
        return self::query()->select('schedule_slot_id')
            ->where('shop_id', '=', $shopId)
            ->whereIn('status', [Constant::ORDER_PENDING, Constant::ORDER_PROCESS, Constant::ORDER_CONFIRMED])
            ->get()
            ->pluck('schedule_slot_id')
            ->toArray();
    }

    public static function getServicesAgainstTimeSlot($slot_id, $shop_id)
    {
        return self::query()->select('shop_stylist_id')
            ->where('schedule_slot_id', '=', $slot_id)
            ->where('shop_id', '=', $shop_id)
            ->whereIn('status', [Constant::ORDER_PENDING, Constant::ORDER_PROCESS, Constant::ORDER_CONFIRMED])
            ->get()
            ->pluck('shop_stylist_id')
            ->toArray();
    }

    public function shopOrderDetail()
    {
        return $this->hasOne(ShopOrderDetail::class, 'shop_order_id', 'id');
    }

    public static function getUserOrders($userId)
    {
        return self::with('shopOrderDetail')->orderBy('id')->get()->toArray();
    }
}
